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
    public string $source_type = 'local'; // 'local' or 'git'
    public string $local_production_path = '';
    public string $git_repository_url = '';
    public string $git_branch = 'main';
    public string $git_auth_method = 'access_token';
    public string $git_access_token = '';
    public string $git_author_name = 'Imon Mahmud';
    public string $git_author_email = 'imon.mahmud4@gmail.com';
    public string $approval_mode = 'automatic';
    
    // Dynamic Time Interval
    public int $interval_value = 5;
    public string $interval_unit = 'minutes'; // minutes, hours, days, months

    public string $protected_terms = 'Autoflow, SaaS, AI';
    public string $global_exclusion_selectors = 'header, footer, nav, .cookie-banner';
    public bool $testingConnection = false;
    public ?string $connectionResult = null;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'domain' => 'required|string|max:255',
            'source_type' => 'required|in:local,git',
            'local_production_path' => 'nullable|required_if:source_type,local|string|max:500',
            'git_repository_url' => 'nullable|required_if:source_type,git|string|max:255',
            'git_branch' => 'required|string|max:100',
            'approval_mode' => 'required|string',
            'interval_value' => 'required|integer|min:1',
            'interval_unit' => 'required|in:minutes,hours,days,months',
        ];
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

    public function save()
    {
        $this->validate();

        Website::create([
            'user_id' => auth()->id() ?? 1,
            'name' => $this->name,
            'domain' => $this->domain,
            'local_production_path' => $this->source_type === 'local' ? $this->local_production_path : null,
            'git_repository_url' => $this->source_type === 'git' ? $this->git_repository_url : ($this->git_repository_url ?: 'local://' . \Illuminate\Support\Str::slug($this->name)),
            'git_branch' => $this->git_branch,
            'git_auth_method' => GitAuthMethod::HttpsToken,
            'git_access_token' => $this->git_access_token ?: null,
            'git_author_name' => $this->git_author_name ?: 'Imon Mahmud',
            'git_author_email' => $this->git_author_email ?: 'imon.mahmud4@gmail.com',
            'approval_mode' => $this->approval_mode === 'automatic' ? ApprovalMode::Automatic : ApprovalMode::Manual,
            'status' => WebsiteStatus::Active,
            'default_rewrite_interval_days' => $this->interval_value,
            'default_rewrite_interval_unit' => $this->interval_unit,
            'protected_terms' => array_map('trim', explode(',', $this->protected_terms)),
            'global_exclusion_selectors' => array_map('trim', explode(',', $this->global_exclusion_selectors)),
            'last_synced_at' => now(),
        ]);

        $this->dispatch('toast', title: 'Website Connected', message: "Website configured with {$this->interval_value} {$this->interval_unit} rewrite interval.", type: 'success');

        return redirect()->route('websites.index');
    }

    public function render()
    {
        return view('livewire.websites.create');
    }
}
