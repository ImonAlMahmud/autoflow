<?php

namespace App\Enums;

enum JobStatus: string
{
    case Scheduled        = 'scheduled';
    case Queued           = 'queued';
    case Preparing        = 'preparing';
    case Extracting       = 'extracting';
    case AiProcessing     = 'ai_processing';
    case Validating       = 'validating';
    case PendingApproval  = 'pending_approval';
    case Committing       = 'committing';
    case Pushing          = 'pushing';
    case Completed        = 'completed';
    case Failed           = 'failed';
    case Skipped          = 'skipped';
    case Cancelled        = 'cancelled';
    case GitConflict      = 'git_conflict';
    case SourceChanged    = 'source_changed';

    public function label(): string
    {
        return match($this) {
            self::Scheduled       => 'Scheduled',
            self::Queued          => 'Queued',
            self::Preparing       => 'Preparing',
            self::Extracting      => 'Extracting',
            self::AiProcessing    => 'AI Processing',
            self::Validating      => 'Validating',
            self::PendingApproval => 'Pending Approval',
            self::Committing      => 'Committing',
            self::Pushing         => 'Pushing',
            self::Completed       => 'Completed',
            self::Failed          => 'Failed',
            self::Skipped         => 'Skipped',
            self::Cancelled       => 'Cancelled',
            self::GitConflict     => 'Git Conflict',
            self::SourceChanged   => 'Source Changed',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Scheduled       => 'blue',
            self::Queued          => 'blue',
            self::Preparing       => 'indigo',
            self::Extracting      => 'indigo',
            self::AiProcessing    => 'violet',
            self::Validating      => 'amber',
            self::PendingApproval => 'amber',
            self::Committing      => 'green',
            self::Pushing         => 'green',
            self::Completed       => 'green',
            self::Failed          => 'red',
            self::Skipped         => 'gray',
            self::Cancelled       => 'gray',
            self::GitConflict     => 'orange',
            self::SourceChanged   => 'orange',
        };
    }

    public function isTerminal(): bool
    {
        return in_array($this, [
            self::Completed,
            self::Failed,
            self::Skipped,
            self::Cancelled,
            self::GitConflict,
            self::SourceChanged,
        ]);
    }

    public function isActive(): bool
    {
        return in_array($this, [
            self::Preparing,
            self::Extracting,
            self::AiProcessing,
            self::Validating,
            self::Committing,
            self::Pushing,
        ]);
    }

    public function pipelineIndex(): int
    {
        return match($this) {
            self::Scheduled, self::Queued => 0,
            self::Preparing   => 1,
            self::Extracting  => 2,
            self::AiProcessing => 3,
            self::Validating  => 4,
            self::PendingApproval => 4,
            self::Committing  => 5,
            self::Pushing     => 6,
            self::Completed   => 7,
            default           => -1,
        };
    }
}
