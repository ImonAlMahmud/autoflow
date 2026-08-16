<?php

namespace App\DTOs\Content;

readonly class ExtractionResult
{
    public function __construct(
        public array  $segments,        // ContentSegment[]
        public string $originalHtml,
        public string $contentHash,
        public int    $totalWordCount,
        public array  $protectedValues,  // extracted factual values
        public array  $metaData,         // title, description, etc.
    ) {}

    public function getSegments(): array
    {
        return $this->segments;
    }

    public function toArray(): array
    {
        return [
            'segments'        => array_map(fn($s) => $s->toArray(), $this->segments),
            'contentHash'     => $this->contentHash,
            'totalWordCount'  => $this->totalWordCount,
            'protectedValues' => $this->protectedValues,
            'metaData'        => $this->metaData,
        ];
    }
}
