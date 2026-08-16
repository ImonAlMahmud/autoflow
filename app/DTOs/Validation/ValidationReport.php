<?php

namespace App\DTOs\Validation;

readonly class ValidationReport
{
    public function __construct(
        public bool  $passed,
        public array $checks, // [check_name => [passed, details]]
        public array $failures = [],
        public array $warnings = [],
    ) {}

    public static function fromChecks(array $checks): self
    {
        $failures = [];
        $warnings = [];

        foreach ($checks as $name => $result) {
            if (! $result['passed'] && ($result['critical'] ?? true)) {
                $failures[] = [
                    'check'   => $name,
                    'message' => $result['message'] ?? 'Validation failed.',
                ];
            } elseif (! $result['passed']) {
                $warnings[] = [
                    'check'   => $name,
                    'message' => $result['message'] ?? 'Warning.',
                ];
            }
        }

        return new self(
            passed:   empty($failures),
            checks:   $checks,
            failures: $failures,
            warnings: $warnings,
        );
    }

    public function toArray(): array
    {
        return [
            'passed'   => $this->passed,
            'checks'   => $this->checks,
            'failures' => $this->failures,
            'warnings' => $this->warnings,
        ];
    }
}
