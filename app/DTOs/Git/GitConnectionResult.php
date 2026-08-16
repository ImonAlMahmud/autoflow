<?php

namespace App\DTOs\Git;

readonly class GitConnectionResult
{
    public function __construct(
        public bool    $success,
        public string  $status,   // connected, auth_failed, repo_not_found, branch_not_found, permission_denied, unknown_error
        public ?string $message = null,
        public ?string $currentCommit = null,
        public array   $branches = [],
    ) {}

    public static function success(string $currentCommit, array $branches = []): self
    {
        return new self(
            success: true,
            status: 'connected',
            message: 'Repository connected successfully.',
            currentCommit: $currentCommit,
            branches: $branches,
        );
    }

    public static function failure(string $status, string $message): self
    {
        return new self(
            success: false,
            status: $status,
            message: $message,
        );
    }

    public function userFriendlyMessage(): string
    {
        return match($this->status) {
            'connected'         => 'Repository is connected and accessible.',
            'auth_failed'       => 'Authentication failed. Check your access token or SSH key.',
            'repo_not_found'    => 'Repository not found. Verify the URL is correct.',
            'branch_not_found'  => 'Branch not found. Check the configured branch name.',
            'permission_denied' => 'Permission denied. Ensure the token has read access.',
            default             => $this->message ?? 'An unknown error occurred.',
        };
    }
}
