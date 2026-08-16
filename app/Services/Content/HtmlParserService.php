<?php

namespace App\Services\Content;

use App\DTOs\Content\ContentSegment;
use App\DTOs\Content\ExtractionResult;
use App\Enums\RewriteScope;
use DOMDocument;
use DOMElement;
use DOMNodeList;
use DOMXPath;
use Illuminate\Support\Str;

class HtmlParserService
{
    /**
     * Parse an HTML file and extract editable content segments.
     *
     * @param string   $htmlContent      Raw HTML string
     * @param array    $rewriteScope     Which scopes are enabled (e.g. ['headings' => true, 'paragraphs' => true])
     * @param array    $excludedSelectors CSS selectors to skip (e.g. ['.footer', '#pricing'])
     * @param array    $globalExclusions  Site-wide CSS exclusions
     * @return ExtractionResult
     */
    public function extract(
        string $htmlContent,
        array  $rewriteScope = [],
        array  $excludedSelectors = [],
        array  $globalExclusions = [],
    ): ExtractionResult {
        $dom = $this->buildDom($htmlContent);
        $xpath = new DOMXPath($dom);

        // Build the list of tags to extract based on scope
        $enabledTags = $this->getEnabledTags($rewriteScope);

        // Collect excluded nodes based on CSS selectors
        $excludedNodes = $this->resolveExcludedNodes(
            $xpath,
            array_merge($excludedSelectors, $globalExclusions)
        );

        $segments = [];
        $counter  = 1;

        foreach ($enabledTags as $tag) {
            $nodes = $xpath->query("//{$tag}");

            if (!$nodes instanceof DOMNodeList) {
                continue;
            }

            foreach ($nodes as $node) {
                if (!$node instanceof DOMElement) {
                    continue;
                }

                // Skip data-ai-ignore elements
                if ($node->getAttribute('data-ai-ignore') === 'true') {
                    continue;
                }

                // Skip excluded nodes
                if ($this->isExcluded($node, $excludedNodes)) {
                    continue;
                }

                // Skip empty nodes
                $text = trim($node->textContent);
                if (empty($text) || strlen($text) < 3) {
                    continue;
                }

                $id = sprintf('segment_%03d', $counter++);

                $segments[] = new ContentSegment(
                    id:         $id,
                    type:       $this->tagToType($tag),
                    content:    $text,
                    tag:        $tag,
                    attributes: $this->preservedAttributes($node),
                    selector:   $this->generateSelector($node),
                );
            }
        }

        // Extract meta data separately
        $metaData = $this->extractMeta($xpath, $rewriteScope);

        // If meta title is in scope, add as segment
        if (!empty($rewriteScope[RewriteScope::MetaTitle->value]) && !empty($metaData['title'])) {
            $segments[] = new ContentSegment(
                id:      sprintf('segment_%03d', $counter++),
                type:    'meta_title',
                content: $metaData['title'],
                tag:     'title',
            );
        }

        if (!empty($rewriteScope[RewriteScope::MetaDesc->value]) && !empty($metaData['description'])) {
            $segments[] = new ContentSegment(
                id:      sprintf('segment_%03d', $counter++),
                type:    'meta_description',
                content: $metaData['description'],
                tag:     'meta',
                attributes: ['name' => 'description'],
            );
        }

        $totalWordCount = array_sum(array_map(fn($s) => $s->wordCount(), $segments));
        $contentHash    = hash('sha256', implode('||', array_map(fn($s) => $s->content, $segments)));

        return new ExtractionResult(
            segments:        $segments,
            originalHtml:    $htmlContent,
            contentHash:     $contentHash,
            totalWordCount:  $totalWordCount,
            protectedValues: [],   // populated by ProtectedValueService
            metaData:        $metaData,
        );
    }

    /**
     * Reconstruct HTML by replacing segment content with rewritten versions.
     * NEVER modifies HTML structure - only text content within matched nodes.
     */
    public function reconstruct(
        string $originalHtml,
        array  $originalSegments,
        array  $rewrittenSegments,
    ): string {
        $dom   = $this->buildDom($originalHtml);
        $xpath = new DOMXPath($dom);

        // Build a lookup map: id => rewritten content
        $rewriteMap = [];
        foreach ($rewrittenSegments as $seg) {
            $rewriteMap[$seg['id']] = $seg['rewritten'];
        }

        foreach ($originalSegments as $segment) {
            if (!isset($rewriteMap[$segment['id']])) {
                continue;
            }

            $rewritten = $rewriteMap[$segment['id']];

            // Find node by selector
            if (!empty($segment['selector'])) {
                $node = $this->findNodeBySelector($xpath, $segment['selector']);

                if ($node) {
                    // Only replace text content, never attributes or structure
                    $this->replaceNodeText($node, $rewritten);
                }
            }
        }

        return $this->domToHtml($dom, $originalHtml);
    }

    /**
     * Build a DOMDocument from HTML string.
     */
    private function buildDom(string $html): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput       = false;

        // Suppress warnings for malformed HTML; use HTML5 entity encoding
        $encoded = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');

        libxml_use_internal_errors(true);
        $dom->loadHTML($encoded, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR);
        libxml_clear_errors();

        return $dom;
    }

    /**
     * Convert DOMDocument back to HTML string.
     */
    private function domToHtml(DOMDocument $dom, string $originalHtml): string
    {
        $output = $dom->saveHTML();

        // Strip the implicit html/body wrappers if original didn't have them
        if (!str_contains(strtolower($originalHtml), '<html')) {
            $output = preg_replace('/^<!DOCTYPE.*?<html[^>]*>\s*/si', '', $output);
            $output = preg_replace('/<\/html>\s*$/si', '', $output);
            $output = preg_replace('/^<body>\s*/si', '', $output);
            $output = preg_replace('/<\/body>\s*$/si', '', $output);
        }

        return $output;
    }

    /**
     * Get HTML tag list from enabled rewrite scope.
     */
    private function getEnabledTags(array $rewriteScope): array
    {
        $tags = [];

        foreach (RewriteScope::cases() as $scope) {
            if (!empty($rewriteScope[$scope->value])) {
                $tags = array_merge($tags, $scope->htmlTags());
            }
        }

        return array_unique($tags);
    }

    /**
     * Map HTML tag to segment type.
     */
    private function tagToType(string $tag): string
    {
        if (in_array($tag, ['h1','h2','h3','h4','h5','h6'])) {
            return 'heading';
        }
        return match($tag) {
            'p'   => 'paragraph',
            'li'  => 'list_item',
            'img' => 'image_alt',
            default => $tag,
        };
    }

    /**
     * Extract meta title and description.
     */
    private function extractMeta(DOMXPath $xpath, array $rewriteScope): array
    {
        $meta = [];

        // Title tag
        $titleNodes = $xpath->query('//title');
        if ($titleNodes->length > 0) {
            $meta['title'] = trim($titleNodes->item(0)->textContent);
        }

        // Meta description
        $descNodes = $xpath->query('//meta[@name="description"]');
        if ($descNodes->length > 0) {
            /** @var DOMElement $descNode */
            $descNode = $descNodes->item(0);
            $meta['description'] = $descNode->getAttribute('content');
        }

        return $meta;
    }

    /**
     * Get attributes that should be preserved from a node.
     */
    private function preservedAttributes(DOMElement $node): array
    {
        $preserved = ['id', 'class', 'href', 'src', 'alt', 'data-*', 'aria-*'];
        $attributes = [];

        foreach ($node->attributes as $attr) {
            $attributes[$attr->name] = $attr->value;
        }

        return $attributes;
    }

    /**
     * Generate a stable CSS selector path for a DOM node.
     */
    private function generateSelector(DOMElement $node): string
    {
        $parts = [];
        $current = $node;

        while ($current && $current->nodeType === XML_ELEMENT_NODE) {
            $tag = $current->tagName;

            // Add ID if present
            $id = $current->getAttribute('id');
            if ($id) {
                $parts[] = "#{$id}";
                break;
            }

            // Find position among siblings
            $position = 1;
            $sibling  = $current->previousSibling;
            while ($sibling) {
                if ($sibling->nodeType === XML_ELEMENT_NODE && $sibling->tagName === $tag) {
                    $position++;
                }
                $sibling = $sibling->previousSibling;
            }

            $parts[] = $position > 1 ? "{$tag}:nth-of-type({$position})" : $tag;
            $current = $current->parentNode;
        }

        return implode(' > ', array_reverse($parts));
    }

    /**
     * Resolve CSS selectors to DOM node arrays for exclusion.
     * Supports simple class, ID, and tag selectors.
     */
    private function resolveExcludedNodes(DOMXPath $xpath, array $selectors): array
    {
        $excluded = [];

        foreach ($selectors as $selector) {
            $xpathExpr = $this->cssToXpath($selector);
            if (!$xpathExpr) {
                continue;
            }

            $nodes = $xpath->query($xpathExpr);
            if ($nodes) {
                foreach ($nodes as $node) {
                    $excluded[] = $node;
                }
            }
        }

        return $excluded;
    }

    /**
     * Convert basic CSS selectors to XPath expressions.
     */
    private function cssToXpath(string $selector): ?string
    {
        $selector = trim($selector);

        // ID selector: #foo
        if (str_starts_with($selector, '#')) {
            $id = substr($selector, 1);
            return "//*[@id='{$id}']";
        }

        // Class selector: .foo
        if (str_starts_with($selector, '.')) {
            $class = substr($selector, 1);
            return "//*[contains(concat(' ', normalize-space(@class), ' '), ' {$class} ')]";
        }

        // Attribute selector: [data-ai-ignore]
        if (preg_match('/^\[([\w-]+)(?:=[\'"]?([^\]\'"]*)[\'"\]?)?\]$/', $selector, $m)) {
            $attr = $m[1];
            $val  = $m[2] ?? null;
            if ($val !== null) {
                return "//*[@{$attr}='{$val}']";
            }
            return "//*[@{$attr}]";
        }

        // Tag selector: footer
        if (preg_match('/^[a-zA-Z][a-zA-Z0-9]*$/', $selector)) {
            return "//{$selector}";
        }

        return null;
    }

    /**
     * Check if a node is within any excluded node.
     */
    private function isExcluded(DOMElement $node, array $excludedNodes): bool
    {
        foreach ($excludedNodes as $excludedNode) {
            // Check if this node is the excluded node or a descendant
            $current = $node;
            while ($current) {
                if ($current->isSameNode($excludedNode)) {
                    return true;
                }
                $current = $current->parentNode;
            }
        }
        return false;
    }

    /**
     * Find a DOM node by CSS selector path.
     */
    private function findNodeBySelector(DOMXPath $xpath, string $selector): ?DOMElement
    {
        $xpathExpr = $this->cssToXpath($selector);
        if (!$xpathExpr) {
            return null;
        }

        $nodes = $xpath->query($xpathExpr);
        if ($nodes && $nodes->length > 0) {
            $node = $nodes->item(0);
            return ($node instanceof DOMElement) ? $node : null;
        }

        return null;
    }

    /**
     * Replace only the text content of a node, preserving all attributes.
     */
    private function replaceNodeText(DOMElement $node, string $newText): void
    {
        // For simple text nodes, just replace the text
        // For nodes with child elements (mixed content), replace only text nodes
        foreach ($node->childNodes as $child) {
            if ($child->nodeType === XML_TEXT_NODE) {
                $child->nodeValue = $newText;
                return;
            }
        }

        // If no text node found, create one
        $node->nodeValue = $newText;
    }
}
