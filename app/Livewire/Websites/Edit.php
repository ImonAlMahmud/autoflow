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

        try {
            $githubApi = new \App\Services\GithubApiService();
            $dummyWebsite = new Website([
                'git_repository_url' => $this->git_repository_url,
                'git_access_token' => $this->git_access_token,
                'git_branch' => $this->git_branch,
            ]);

            $files = $githubApi->listHtmlFiles($dummyWebsite);
            if (!empty($files)) {
                $this->connectionResult = 'Successfully connected to GitHub. Found ' . count($files) . ' HTML pages ready for automated AI refresh.';
                $this->dispatch('toast', title: 'GitHub Handshake Successful 🚀', message: "Verified repository & found " . count($files) . " HTML pages.", type: 'success');
            } else {
                $this->connectionResult = 'Could not access repository. Please check your GitHub Repository URL, Target Branch, and Personal Access Token (PAT).';
                $this->dispatch('toast', title: 'GitHub Auth Failed', message: 'Unable to access repository with provided token.', type: 'danger');
            }
        } catch (\Throwable $e) {
            $this->connectionResult = 'Connection error: ' . $e->getMessage();
            $this->dispatch('toast', title: 'Connection Error', message: $e->getMessage(), type: 'danger');
        }

        $this->testingConnection = false;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|max:255',
            'git_repository_url' => 'required|string|max:255',
            'git_branch' => 'required|string|max:100',
            'git_access_token' => 'nullable|string|max:255',
            'approval_mode' => 'required|string',
            'notification_email' => 'nullable|email|max:255',
            'interval_value' => 'required|integer|min:1',
            'interval_unit' => 'required|in:minutes,hours,days,months',
        ]);

        try {
            $target = Website::find($this->websiteId ?? 1) ?? Website::first();

            if ($target) {
                $protectedTerms = array_values(array_filter(array_map('trim', explode(',', (string) $this->protected_terms))));
                $exclusionSelectors = array_values(array_filter(array_map('trim', explode(',', (string) $this->global_exclusion_selectors))));

                $target->update([
                    'name' => $this->name,
                    'domain' => $this->domain,
                    'local_production_path' => null,
                    'git_repository_url' => $this->git_repository_url,
                    'git_branch' => $this->git_branch,
                    'git_access_token' => $this->git_access_token ?: null,
                    'git_author_name' => $this->git_author_name ?: 'Imon Mahmud',
                    'git_author_email' => $this->git_author_email ?: 'imon.mahmud4@gmail.com',
                    'git_auth_method' => GitAuthMethod::HttpsToken,
                    'approval_mode' => $this->approval_mode === 'automatic' ? ApprovalMode::Automatic : ApprovalMode::Manual,
                    'notification_email' => $this->notification_email ?: null,
                    'default_rewrite_interval_days' => (int) $this->interval_value,
                    'default_rewrite_interval_unit' => $this->interval_unit,
                    'protected_terms' => $protectedTerms,
                    'global_exclusion_selectors' => $exclusionSelectors,
                ]);

                $this->dispatch('toast', title: 'Settings Saved', message: "Website configuration updated successfully.", type: 'success');

                return redirect()->route('websites.show', $target->id);
            } else {
                $this->dispatch('toast', title: 'Error', message: 'Website not found.', type: 'danger');
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Failed to update website: " . $e->getMessage());
            $this->dispatch('toast', title: 'Update Failed', message: "Could not save changes: " . $e->getMessage(), type: 'danger');
        }
    }

    public function deleteWebsite()
    {
        try {
            $target = Website::find($this->websiteId ?? 1) ?? Website::first();

            if ($target) {
                $target->delete();
            }

            $this->dispatch('toast', title: 'Website Disconnected', message: 'Website and tracking configuration removed.', type: 'warning');

            return redirect()->route('websites.index');
        } catch (\Throwable $e) {
            $this->dispatch('toast', title: 'Delete Failed', message: $e->getMessage(), type: 'danger');
        }
    }

    public function render()
    {
        return view('livewire.websites.edit', [
            'websiteId' => $this->websiteId,
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
