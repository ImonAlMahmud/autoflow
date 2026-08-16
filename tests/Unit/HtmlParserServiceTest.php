<?php

namespace Tests\Unit;

use App\Services\Content\HtmlParserService;
use PHPUnit\Framework\TestCase;

class HtmlParserServiceTest extends TestCase
{
    public function test_extracts_headings_and_paragraphs()
    {
        $parser = new HtmlParserService();
        $html = "<html><body><h1>Welcome</h1><p>This is a test paragraph.</p></body></html>";

        $result = $parser->extract($html, [
            'headings' => true,
            'paragraphs' => true,
        ]);

        $segments = $result->getSegments();
        $this->assertCount(2, $segments);
        $this->assertEquals('Welcome', $segments[0]->content);
        $this->assertEquals('This is a test paragraph.', $segments[1]->content);
    }
}
