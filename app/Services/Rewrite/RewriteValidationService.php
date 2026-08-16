<?php

namespace App\Services\Rewrite;

use App\DTOs\Validation\ValidationReport;
use App\Services\Content\ProtectedValueService;
use Illuminate\Support\Facades\Log;

class RewriteValidationService
{
    public function __construct(
        private readonly ProtectedValueService $protectedValueService,
    ) {}

    /**
     * Run the full validation pipeline on an AI rewrite response.
     *
     * @param array  $originalSegments   Original extracted segments (as arrays)
     * @param array  $rewrittenSegments  AI response segments
     * @param string $originalHtml       Original HTML for structural comparison
     * @param string $rewrittenHtml      Reconstructed HTML
     * @param array  $protectedValues    Pre-extracted protected values
     * @param array  $options            Configurable thresholds
     */
    public function validate(
        array  $originalSegments,
        array  $rewrittenSegments,
        string $originalHtml,
        string $rewrittenHtml,
        array  $protectedValues = [],
        array  $options = [],
    ): ValidationReport {
        $maxExpansion = $options['max_expansion_percent'] ?? config('ai.rewrite.max_expansion_percent', 30);
        $maxReduction = $options['max_reduction_percent'] ?? config('ai.rewrite.max_reduction_percent', 25);

        $checks = [];

        // 1. JSON Validity (already parsed, so check structure)
        $checks['json_validity'] = $this->checkJsonValidity($rewrittenSegments);

        // 2. Segment Completeness
        $checks['segment_completeness'] = $this->checkSegmentCompleteness(
            $originalSegments,
            $rewrittenSegments
        );

        // 3. Protected Values
        $checks['protected_values'] = $this->checkProtectedValues(
            $protectedValues,
            $rewrittenSegments
        );

        // 4. HTML Structure
        $checks['html_structure'] = $this->checkHtmlStructure(
            $originalHtml,
            $rewrittenHtml
        );

        // 5. Links Preserved
        $checks['links_preserved'] = $this->checkLinksPreserved(
            $originalHtml,
            $rewrittenHtml
        );

        // 6. Word Count
        $checks['word_count'] = $this->checkWordCount(
            $originalSegments,
            $rewrittenSegments,
            $maxExpansion,
            $maxReduction
        );

        // 7. Content Quality
        $checks['content_quality'] = $this->checkContentQuality($rewrittenSegments);

        // 8. Empty Content
        $checks['no_empty_segments'] = $this->checkNoEmptySegments($rewrittenSegments);

        return ValidationReport::fromChecks($checks);
    }

    private function checkJsonValidity(array $segments): array
    {
        $valid = !empty($segments) && isset($segments[0]['id'], $segments[0]['rewritten']);

        return [
            'passed'   => $valid,
            'critical' => true,
            'message'  => $valid ? null : 'AI response segments are missing required fields (id, rewritten).',
        ];
    }

    private function checkSegmentCompleteness(array $original, array $rewritten): array
    {
        $originalIds  = array_column($original, 'id');
        $rewrittenIds = array_column($rewritten, 'id');
        $missing      = array_diff($originalIds, $rewrittenIds);

        return [
            'passed'   => empty($missing),
            'critical' => true,
            'message'  => empty($missing)
                ? null
                : 'Missing segments in AI response: ' . implode(', ', $missing),
            'details'  => ['missing_ids' => array_values($missing)],
        ];
    }

    private function checkProtectedValues(array $protectedValues, array $rewrittenSegments): array
    {
        if (empty($protectedValues)) {
            return ['passed' => true, 'critical' => true, 'message' => null];
        }

        $rewrittenTexts = array_column($rewrittenSegments, 'rewritten');
        $result = $this->protectedValueService->compare($protectedValues, $rewrittenTexts);

        return [
            'passed'   => $result['passed'],
            'critical' => true,
            'message'  => $result['passed']
                ? null
                : 'Protected values were modified or removed: ' . implode(', ', array_column($result['missing'], 'value')),
            'details'  => $result,
        ];
    }

    private function checkHtmlStructure(string $originalHtml, string $rewrittenHtml): array
    {
        // Compare structural elements: tags, IDs, classes, hrefs, srcs
        $originalLinks    = $this->extractLinks($originalHtml);
        $rewrittenLinks   = $this->extractLinks($rewrittenHtml);
        $originalSrcs     = $this->extractSrcs($originalHtml);
        $rewrittenSrcs    = $this->extractSrcs($rewrittenHtml);
        $originalIds      = $this->extractIds($originalHtml);
        $rewrittenIds     = $this->extractIds($rewrittenHtml);

        $issues = [];

        if ($originalLinks !== $rewrittenLinks) {
            $issues[] = 'Link hrefs changed';
        }
        if ($originalSrcs !== $rewrittenSrcs) {
            $issues[] = 'Image/script src attributes changed';
        }
        if ($originalIds !== $rewrittenIds) {
            $issues[] = 'Element IDs changed';
        }

        return [
            'passed'   => empty($issues),
            'critical' => true,
            'message'  => empty($issues) ? null : implode('; ', $issues),
            'details'  => ['issues' => $issues],
        ];
    }

    private function checkLinksPreserved(string $originalHtml, string $rewrittenHtml): array
    {
        $original  = $this->extractLinks($originalHtml);
        $rewritten = $this->extractLinks($rewrittenHtml);
        $passed    = $original === $rewritten;

        return [
            'passed'   => $passed,
            'critical' => true,
            'message'  => $passed ? null : 'One or more hyperlinks were modified.',
            'details'  => [
                'original_count'  => count($original),
                'rewritten_count' => count($rewritten),
            ],
        ];
    }

    private function checkWordCount(
        array $original,
        array $rewritten,
        int   $maxExpansion,
        int   $maxReduction
    ): array {
        $originalWords  = array_sum(array_map(fn($s) => str_word_count($s['content'] ?? ''), $original));
        $rewrittenWords = array_sum(array_map(fn($s) => str_word_count($s['rewritten'] ?? ''), $rewritten));

        if ($originalWords === 0) {
            return ['passed' => true, 'critical' => false, 'message' => null];
        }

        $changePercent = (($rewrittenWords - $originalWords) / $originalWords) * 100;

        $passed = $changePercent <= $maxExpansion && $changePercent >= -$maxReduction;

        return [
            'passed'   => $passed,
            'critical' => false,
            'message'  => $passed
                ? null
                : sprintf(
                    'Word count changed by %.1f%% (original: %d, rewritten: %d). Limits: +%d%% / -%d%%.',
                    $changePercent, $originalWords, $rewrittenWords, $maxExpansion, $maxReduction
                ),
            'details'  => [
                'original_words'  => $originalWords,
                'rewritten_words' => $rewrittenWords,
                'change_percent'  => round($changePercent, 1),
            ],
        ];
    }

    private function checkContentQuality(array $rewrittenSegments): array
    {
        $issues    = [];
        $patterns  = config('ai.rewrite.placeholder_patterns', []);
        $fencePattern = config('ai.rewrite.markdown_fence_pattern');
        $commentaryPatterns = config('ai.rewrite.ai_commentary_patterns', []);

        foreach ($rewrittenSegments as $seg) {
            $text = $seg['rewritten'] ?? '';

            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $text)) {
                    $issues[] = "Placeholder text detected in segment {$seg['id']}";
                }
            }

            if ($fencePattern && preg_match($fencePattern, $text)) {
                $issues[] = "Markdown fence in segment {$seg['id']}";
            }

            foreach ($commentaryPatterns as $pattern) {
                if (preg_match($pattern, $text)) {
                    $issues[] = "AI commentary detected in segment {$seg['id']}";
                    break;
                }
            }
        }

        return [
            'passed'   => empty($issues),
            'critical' => true,
            'message'  => empty($issues) ? null : implode('; ', $issues),
            'details'  => ['issues' => $issues],
        ];
    }

    private function checkNoEmptySegments(array $rewrittenSegments): array
    {
        $empty = [];

        foreach ($rewrittenSegments as $seg) {
            if (empty(trim($seg['rewritten'] ?? ''))) {
                $empty[] = $seg['id'];
            }
        }

        return [
            'passed'   => empty($empty),
            'critical' => true,
            'message'  => empty($empty)
                ? null
                : 'Empty rewritten content in segments: ' . implode(', ', $empty),
        ];
    }

    // --- HTML parsing helpers ---

    private function extractLinks(string $html): array
    {
        preg_match_all('/href=[\'"]([^\'"]+)[\'"]/i', $html, $matches);
        $links = $matches[1];
        sort($links);
        return $links;
    }

    private function extractSrcs(string $html): array
    {
        preg_match_all('/src=[\'"]([^\'"]+)[\'"]/i', $html, $matches);
        $srcs = $matches[1];
        sort($srcs);
        return $srcs;
    }

    private function extractIds(string $html): array
    {
        preg_match_all('/id=[\'"]([^\'"]+)[\'"]/i', $html, $matches);
        $ids = $matches[1];
        sort($ids);
        return $ids;
    }
}
