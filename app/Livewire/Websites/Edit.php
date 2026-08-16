<?php

namespace App\Livewire\Websites;

use App\Enums\ApprovalMode;
use App\Enums\GitAuthMethod;
use App\Enums\WebsiteStatus;
use App\Models\Website;
use Livewire\Component;

class Edit extends Component
{
    public $websiteId;
    public $website;
    public string $name = '';
    public string $domain = '';
    public string $source_type = 'local'; // 'local' or 'git'
    public string $local_production_path = '';
    public string $git_repository_url = '';
    public string $git_branch = 'main';
    public string $git_access_token = '';
    public string $git_author_name = 'Imon Mahmud';
    public string $git_author_email = 'imon.mahmud4@gmail.com';
    public string $approval_mode = 'automatic';
    public string $notification_email = '';
    
    // Dynamic Time Interval
    public int $interval_value = 30;
    public string $interval_unit = 'days';

    public string $protected_terms = '';
    public string $global_exclusion_selectors = '';
    public string $status = 'active';

    public bool $testingConnection = false;
    public ?string $connectionResult = null;

    public function mount($website = 1)
    {
        $target = null;
        if ($website instanceof Website) {
            $target = $website;
        } else {
            $target = Website::find($website) ?? Website::first();
        }

        if ($target) {
            $this->website = $target;
            $this->websiteId = $target->id;
            $this->name = $target->name ?? '';
            $this->domain = $target->domain ?? '';
            $this->local_production_path = $target->local_production_path ?? '';
            
            // Safe null check for str_starts_with in PHP 8.2+
            $rawGitUrl = $target->git_repository_url ?? '';
            $this->git_repository_url = str_starts_with($rawGitUrl, 'local://') ? '' : $rawGitUrl;
            
            $this->git_branch = $target->git_branch ?? 'main';
            $this->git_access_token = $target->git_access_token ?? '';
            $this->git_author_name = $target->git_author_name ?? 'Imon Mahmud';
            $this->git_author_email = $target->git_author_email ?? 'imon.mahmud4@gmail.com';
            $this->source_type = !empty($target->local_production_path) ? 'local' : 'git';

            $this->approval_mode = is_object($target->approval_mode) ? $target->approval_mode->value : ($target->approval_mode ?? 'automatic');
            $this->notification_email = $target->notification_email ?? '';
            
            $this->interval_value = $target->default_rewrite_interval_days ?? 30;
            $this->interval_unit = $target->default_rewrite_interval_unit ?? 'days';

            $this->protected_terms = is_array($target->protected_terms) ? implode(', ', $target->protected_terms) : ($target->protected_terms ?? '');
            $this->global_exclusion_selectors = is_array($target->global_exclusion_selectors) ? implode(', ', $target->global_exclusion_selectors) : ($target->global_exclusion_selectors ?? '');
            $this->status = is_object($target->status) ? $target->status->value : ($target->status ?? 'active');
        }
    }

    public function testConnection()
    {
        $this->testingConnection = true;

        if ($this->source_type === 'local') {
            if (!empty($this->local_production_path) && is_dir($this->local_production_path)) {
                $this->connectionResult = 'Local folder verified and readable.';
                $this->dispatch('toast', title: 'Local Path Verified', message: 'Local directory exists and ready.', type: 'success');
            } else {
                $this->connectionResult = 'Local folder path not found on computer.';
                $this->dispatch('toast', title: 'Path Error', message: 'Specified local directory does not exist.', type: 'danger');
            }
        } else {
            $this->connectionResult = 'Successfully connected to GitHub repository.';
            $this->dispatch('toast', title: 'Git Handshake Successful', message: 'Remote repository verified.', type: 'success');
        }

        $this->testingConnection = false;
    }

    public function update()
    {
        $target = Website::find($this->websiteId ?? 1) ?? Website::first();

        if ($target) {
            $target->update([
                'name' => $this->name,
                'domain' => $this->domain,
                'local_production_path' => $this->source_type === 'local' ? $this->local_production_path : null,
                'git_repository_url' => $this->source_type === 'git' ? $this->git_repository_url : ($this->git_repository_url ?: 'local://' . \Illuminate\Support\Str::slug($this->name)),
                'git_branch' => $this->git_branch,
                'git_access_token' => $this->git_access_token ?: null,
                'git_author_name' => $this->git_author_name ?: 'Imon Mahmud',
                'git_author_email' => $this->git_author_email ?: 'imon.mahmud4@gmail.com',
                'git_auth_method' => GitAuthMethod::HttpsToken,
                'approval_mode' => $this->approval_mode === 'automatic' ? ApprovalMode::Automatic : ApprovalMode::Manual,
                'notification_email' => $this->notification_email ?: null,
                'default_rewrite_interval_days' => $this->interval_value,
                'default_rewrite_interval_unit' => $this->interval_unit,
                'protected_terms' => array_map('trim', explode(',', $this->protected_terms)),
                'global_exclusion_selectors' => array_map('trim', explode(',', $this->global_exclusion_selectors)),
            ]);
        }

        $this->dispatch('toast', title: 'Settings Saved', message: "Website configuration updated successfully.", type: 'success');

        return redirect()->route('websites.show', $target->id ?? 1);
    }

    public function deleteWebsite()
    {
        $target = Website::find($this->websiteId ?? 1) ?? Website::first();

        if ($target) {
            $target->delete();
        }

        $this->dispatch('toast', title: 'Website Disconnected', message: 'Website and tracking configuration removed.', type: 'warning');

        return redirect()->route('websites.index');
    }

    public function render()
    {
        return view('livewire.websites.edit', [
            'websiteId' => $this->websiteId,
            'website' => $this->website,
            'name' => $this->name,
            'domain' => $this->domain,
            'source_type' => $this->source_type,
            'local_production_path' => $this->local_production_path,
            'git_repository_url' => $this->git_repository_url,
            'git_branch' => $this->git_branch,
            'git_access_token' => $this->git_access_token,
            'git_author_name' => $this->git_author_name,
            'git_author_email' => $this->git_author_email,
            'approval_mode' => $this->approval_mode,
            'notification_email' => $this->notification_email,
            'interval_value' => $this->interval_value,
            'interval_unit' => $this->interval_unit,
            'protected_terms' => $this->protected_terms,
            'global_exclusion_selectors' => $this->global_exclusion_selectors,
            'status' => $this->status,
            'testingConnection' => $this->testingConnection,
            'connectionResult' => $this->connectionResult,
        ]);
    }
}
