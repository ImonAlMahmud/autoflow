<?php

namespace Tests\Unit;

use App\Services\Content\ProtectedValueService;
use PHPUnit\Framework\TestCase;

class ProtectedValueServiceTest extends TestCase
{
    public function test_extracts_phone_numbers_and_emails()
    {
        $service = new ProtectedValueService();
        $text = "Contact us at support@ideomet.com or call +1 (800) 555-0199 for pricing.";

        $extracted = $service->extract($text);

        $this->assertArrayHasKey('email', $extracted);
        $this->assertContains('support@ideomet.com', $extracted['email']);
        $this->assertArrayHasKey('phone', $extracted);
        $this->assertContains('+1 (800) 555-0199', $extracted['phone']);
    }

    public function test_detects_missing_protected_values()
    {
        $service = new ProtectedValueService();
        $originalValues = [
            ['type' => 'email', 'value' => 'support@ideomet.com'],
            ['type' => 'phone', 'value' => '+1 (800) 555-0199'],
        ];

        $rewrittenTexts = ["Reach our team anytime via email or phone."];

        $result = $service->compare($originalValues, $rewrittenTexts);

        $this->assertFalse($result['passed']);
        $this->assertCount(2, $result['missing']);
    }
}
