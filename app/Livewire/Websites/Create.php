<?php

namespace App\Livewire\Websites;

use App\Enums\ApprovalMode;
use App\Enums\GitAuthMethod;
use App\Enums\WebsiteStatus;
use App\Models\Website;
use Livewire\Component;

class Create extends Component
{
    public string $name = '';
    public string $domain = '';
    public string $git_repository_url = '';
    public string $git_branch = 'main';
    public string $git_auth_method = 'access_token';
    public string $git_access_token = '';
    public string $git_author_name = '';
    public string $git_author_email = '';
    public string $approval_mode = 'automatic';
    public string $notification_email = '';
    
    // Dynamic Time Interval
    public int $interval_value = 5;
    public string $interval_unit = 'minutes'; // minutes, hours, days, months

    public string $protected_terms = '';
    public string $global_exclusion_selectors = '';
    public bool $testingConnection = false;
    public ?string $connectionResult = null;

    public function mount()
    {
        // Keep inputs clean for user entry
        $this->git_author_name = \App\Models\SystemSetting::get('global_github_author_name', '') ?? '';
        $this->git_author_email = \App\Models\SystemSetting::get('global_github_author_email', '') ?? '';
    }

    protected function rules(): array
    {
        $hasGlobalToken = !empty(\App\Models\SystemSetting::get('global_github_token', ''));

        return [
            'name' => 'required|string|max:255',
            'domain' => 'required|string|max:255',
            'git_repository_url' => 'required|string|max:255',
            'git_branch' => 'required|string|max:100',
            'git_access_token' => $hasGlobalToken ? 'nullable|string|max:255' : 'required|string|max:255',
            'approval_mode' => 'required|string',
            'notification_email' => 'nullable|email|max:255',
            'interval_value' => 'required|integer|min:1',
            'interval_unit' => 'required|in:minutes,hours,days,months',
        ];
    }

    public function testConnection()
    {
        $this->testingConnection = true;

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

        $this->testingConnection = false;
    }

    public function save()
    {
        $this->validate();

        $user = auth()->user();
        if ($user && !$user->canAddWebsite()) {
            $this->dispatch('toast', 
                title: 'Quota Reached ⚠️', 
                message: "You have reached your limit of {$user->websites_limit} websites on the {$user->plan_badge}. Upgrade to Pro/Enterprise for higher limits!", 
                type: 'danger'
            );
            return redirect()->route('subscription');
        }

        $website = Website::create([
            'user_id' => auth()->id() ?? 1,
            'name' => $this->name,
            'domain' => $this->domain,
            'local_production_path' => null,
            'git_repository_url' => $this->git_repository_url,
            'git_branch' => $this->git_branch,
            'git_auth_method' => GitAuthMethod::HttpsToken,
            'git_access_token' => $this->git_access_token,
            'git_author_name' => $this->git_author_name ?: 'Imon Mahmud',
            'git_author_email' => $this->git_author_email ?: 'imon.mahmud4@gmail.com',
            'approval_mode' => $this->approval_mode === 'automatic' ? ApprovalMode::Automatic : ApprovalMode::Manual,
            'notification_email' => $this->notification_email ?: null,
            'status' => WebsiteStatus::Active,
            'default_rewrite_interval_days' => $this->interval_value,
            'default_rewrite_interval_unit' => $this->interval_unit,
            'protected_terms' => array_map('trim', explode(',', $this->protected_terms)),
            'global_exclusion_selectors' => array_map('trim', explode(',', $this->global_exclusion_selectors)),
            'last_synced_at' => now(),
        ]);

        // Auto-discover pages from GitHub
        $githubApi = new \App\Services\GithubApiService();
        $files = $githubApi->listHtmlFiles($website);
        foreach ($files as $rel) {
            $cleanName = trim(str_replace(['.html', '-', '_'], ['', ' ', ' '], $rel), '/ ');
            $parts = explode('/', $cleanName);
            $friendlyName = implode(' › ', array_map('ucwords', $parts));

            \App\Models\WebsitePage::create([
                'website_id' => $website->id,
                'path' => $rel,
                'friendly_name' => $friendlyName ?: 'Home Page',
                'rewrite_enabled' => true,
                'rewrite_interval_days' => $website->default_rewrite_interval_days ?? 5,
            ]);
        }

        $this->dispatch('toast', title: 'Website Connected to GitHub 🚀', message: "Registered {$website->name} and discovered " . count($files) . " HTML pages.", type: 'success');

        return redirect()->route('websites.index');
    }

    public function render()
    {
        return view('livewire.websites.create');
    }
}
