<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Git Executable
    |--------------------------------------------------------------------------
    */
    'executable' => env('GIT_EXECUTABLE', 'git'),

    /*
    |--------------------------------------------------------------------------
    | Git Timeout (seconds per operation)
    |--------------------------------------------------------------------------
    */
    'timeout' => (int) env('GIT_TIMEOUT', 60),

    /*
    |--------------------------------------------------------------------------
    | Workspace Configuration
    |--------------------------------------------------------------------------
    */
    'workspace_root'           => env('WORKSPACE_ROOT', '/opt/ai-content-refresh/workspaces'),
    'workspace_max_age_hours'  => (int) env('WORKSPACE_MAX_AGE_HOURS', 24),

    /*
    |--------------------------------------------------------------------------
    | Commit Author
    |--------------------------------------------------------------------------
    */
    'commit_author_name'  => 'Autoflow Bot',
    'commit_author_email' => 'bot@autoflow.local',

    /*
    |--------------------------------------------------------------------------
    | Repository URL Allowlist (regex patterns)
    | Only these URL patterns are accepted for repository URLs.
    |--------------------------------------------------------------------------
    */
    'allowed_repo_patterns' => [
        '/^https?:\/\//',
        '/^git@/',
    ],

    /*
    |--------------------------------------------------------------------------
    | Branch Name Validation
    | Only alphanumeric, dash, slash, dot, underscore allowed.
    |--------------------------------------------------------------------------
    */
    'branch_pattern' => '/^[a-zA-Z0-9\-_\.\/]+$/',

];
