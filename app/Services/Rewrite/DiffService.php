<?php

namespace App\Services\Rewrite;

class DiffService
{
    /**
     * Generate a structured diff between original and rewritten segments.
     *
     * @param array $originalSegments  [{id, content, ...}]
     * @param array $rewrittenSegments [{id, original, rewritten}]
     * @return array  Structured diff data
     */
    public function generateSegmentDiff(array $originalSegments, array $rewrittenSegments): array
    {
        $originalMap = [];
        foreach ($originalSegments as $seg) {
            $id = $seg['id'] ?? ($seg->id ?? null);
            if ($id) {
                $originalMap[$id] = $seg['content'] ?? ($seg->content ?? '');
            }
        }

        $diffs = [];

        foreach ($rewrittenSegments as $seg) {
            $id       = $seg['id'];
            $original = $originalMap[$id] ?? '';
            $rewritten = $seg['rewritten'] ?? '';

            if ($original === $rewritten) {
                $diffs[] = [
                    'id'       => $id,
                    'changed'  => false,
                    'original' => $original,
                    'rewritten'=> $rewritten,
                    'lines'    => [],
                ];
                continue;
            }

            $lineDiff = $this->computeLineDiff($original, $rewritten);

            $diffs[] = [
                'id'        => $id,
                'changed'   => true,
                'original'  => $original,
                'rewritten' => $rewritten,
                'lines'     => $lineDiff,
                'word_diff' => $this->computeWordDiff($original, $rewritten),
            ];
        }

        return $diffs;
    }

    /**
     * Compute a simple line-level diff.
     * Returns array of ['type' => 'removed'|'added'|'unchanged', 'text' => '...']
     */
    public function computeLineDiff(string $original, string $rewritten): array
    {
        $originalLines  = explode("\n", wordwrap($original, 80));
        $rewrittenLines = explode("\n", wordwrap($rewritten, 80));

        return $this->lcs($originalLines, $rewrittenLines);
    }

    /**
     * Compute word-level diff for inline highlighting.
     */
    public function computeWordDiff(string $original, string $rewritten): array
    {
        $originalWords  = explode(' ', $original);
        $rewrittenWords = explode(' ', $rewritten);

        return $this->lcs($originalWords, $rewrittenWords);
    }

    /**
     * Longest Common Subsequence diff algorithm.
     */
    private function lcs(array $a, array $b): array
    {
        $n = count($a);
        $m = count($b);

        // Build LCS table
        $dp = array_fill(0, $n + 1, array_fill(0, $m + 1, 0));

        for ($i = 1; $i <= $n; $i++) {
            for ($j = 1; $j <= $m; $j++) {
                if ($a[$i - 1] === $b[$j - 1]) {
                    $dp[$i][$j] = $dp[$i - 1][$j - 1] + 1;
                } else {
                    $dp[$i][$j] = max($dp[$i - 1][$j], $dp[$i][$j - 1]);
                }
            }
        }

        // Backtrack to build diff
        $diff = [];
        $i    = $n;
        $j    = $m;

        while ($i > 0 || $j > 0) {
            if ($i > 0 && $j > 0 && $a[$i - 1] === $b[$j - 1]) {
                array_unshift($diff, ['type' => 'unchanged', 'text' => $a[$i - 1]]);
                $i--;
                $j--;
            } elseif ($j > 0 && ($i === 0 || $dp[$i][$j - 1] >= $dp[$i - 1][$j])) {
                array_unshift($diff, ['type' => 'added', 'text' => $b[$j - 1]]);
                $j--;
            } else {
                array_unshift($diff, ['type' => 'removed', 'text' => $a[$i - 1]]);
                $i--;
            }
        }

        return $diff;
    }
}
