<?php

namespace App\DTOs\Content;

readonly class ContentSegment
{
    public function __construct(
        public string $id,
        public string $type,      // heading, paragraph, list_item, meta_title, meta_description, image_alt
        public string $content,
        public string $tag,       // actual HTML tag: h1, h2, p, li, etc.
        public array  $attributes = [], // preserved attributes
        public ?string $selector = null, // CSS path for reconstruction
    ) {}

    public function toArray(): array
    {
        return [
            'id'         => $this->id,
            'type'       => $this->type,
            'content'    => $this->content,
            'tag'        => $this->tag,
            'attributes' => $this->attributes,
            'selector'   => $this->selector,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id:         $data['id'],
            type:       $data['type'],
            content:    $data['content'],
            tag:        $data['tag'],
            attributes: $data['attributes'] ?? [],
            selector:   $data['selector'] ?? null,
        );
    }

    public function wordCount(): int
    {
        return str_word_count(strip_tags($this->content));
    }
}
