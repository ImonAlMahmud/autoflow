<?php

namespace App\Services\Content;

class ProtectedValueService
{
    /**
     * Regex patterns to detect protected factual values.
     */
    private array $patterns = [
        'phone'      => '/(?:\+?\d{1,3}[\s\-\.]?)?(?:\(?\d{1,4}\)?[\s\-\.]?)?\d{3,4}[\s\-\.]?\d{3,4}(?:[\s\-\.]?\d{1,4})?/',
        'email'      => '/[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}/',
        'url'        => '/https?:\/\/[^\s<>"]+/',
        'currency'   => '/\$[\d,]+(?:\.\d{2})?|[\d,]+(?:\.\d{2})?\s?(?:USD|GBP|EUR|AUD|CAD)/i',
        'percentage' => '/\d+(?:\.\d+)?\s?%/',
        'date'       => '/(?:\d{1,2}[\-\/]\d{1,2}[\-\/]\d{2,4}|(?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)[a-z]*\.?\s+\d{1,2}(?:,\s+\d{4})?)/i',
        'year'       => '/\b(?:19|20)\d{2}\b/',
        'number'     => '/\b\d{4,}\b/',  // significant numbers (4+ digits)
    ];

    /**
     * Extract all protected values from text content.
     *
     * @return array [type => [value, ...]]
     */
    public function extract(string $text): array
    {
        $extracted = [];

        foreach ($this->patterns as $type => $pattern) {
            preg_match_all($pattern, $text, $matches);
            if (!empty($matches[0])) {
                $extracted[$type] = array_values(array_unique($matches[0]));
            }
        }

        return $extracted;
    }

    /**
     * Extract protected values from an array of segment content strings.
     */
    public function extractFromSegments(array $segments): array
    {
        $allValues = [];
        $allText   = implode(' ', array_map(fn($s) => $s['content'] ?? ($s->content ?? ''), $segments));

        $extracted = $this->extract($allText);

        foreach ($extracted as $type => $values) {
            foreach ($values as $value) {
                $allValues[] = [
                    'type'  => $type,
                    'value' => trim($value),
                ];
            }
        }

        return $allValues;
    }

    /**
     * Compare protected values before and after rewrite.
     * Returns [passed => bool, missing => [], changed => []]
     */
    public function compare(array $originalValues, array $rewrittenText): array
    {
        $missing = [];
        $changed = [];

        foreach ($originalValues as $item) {
            $value = $item['value'];
            $found = false;

            foreach ($rewrittenText as $text) {
                if (str_contains($text, $value)) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $missing[] = [
                    'type'  => $item['type'],
                    'value' => $value,
                ];
            }
        }

        return [
            'passed'  => empty($missing),
            'missing' => $missing,
            'changed' => $changed,
        ];
    }

    /**
     * Merge user-defined protected terms with auto-extracted values.
     */
    public function mergeWithCustomTerms(array $extractedValues, array $customTerms): array
    {
        foreach ($customTerms as $term) {
            if (!empty($term)) {
                $extractedValues[] = [
                    'type'  => 'custom',
                    'value' => $term,
                ];
            }
        }

        return $extractedValues;
    }
}
