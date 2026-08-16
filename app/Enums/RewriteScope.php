<?php

namespace App\Enums;

enum RewriteScope: string
{
    case Headings    = 'headings';
    case Paragraphs  = 'paragraphs';
    case ListItems   = 'list_items';
    case MetaTitle   = 'meta_title';
    case MetaDesc    = 'meta_description';
    case ImageAlt    = 'image_alt';

    public function label(): string
    {
        return match($this) {
            self::Headings   => 'Headings (h1-h6)',
            self::Paragraphs => 'Paragraphs',
            self::ListItems  => 'List Items',
            self::MetaTitle  => 'Meta Title',
            self::MetaDesc   => 'Meta Description',
            self::ImageAlt   => 'Image Alt Text',
        };
    }

    public static function defaults(): array
    {
        return [
            self::Headings->value   => true,
            self::Paragraphs->value => true,
            self::ListItems->value  => true,
            self::MetaTitle->value  => false,
            self::MetaDesc->value   => false,
            self::ImageAlt->value   => false,
        ];
    }

    public function htmlTags(): array
    {
        return match($this) {
            self::Headings   => ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
            self::Paragraphs => ['p'],
            self::ListItems  => ['li'],
            self::MetaTitle  => [],
            self::MetaDesc   => [],
            self::ImageAlt   => ['img'],
        };
    }
}
