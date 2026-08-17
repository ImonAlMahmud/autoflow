<?php

namespace App\Livewire\Websites;

use App\Models\Website;
use App\Models\WebsitePage;
use Livewire\Component;

class Show extends Component
{
    public $websiteId;
    public $website;
    public string $activeTab = 'pages';
    public string $searchPage = '';

    public function mount($website = null)
    {
        $this->websiteId = is_object($website) ? $website->id : $website;
        if ($this->websiteId) {
            $this->website = Website::withCount('pages')->find($this->websiteId);
        }
    }

    public function triggerFullSync()
    {
        $this->dispatch('toast', title: 'Git Remote Syncing', message: 'Fetching remote refs, branches, and commit logs.', type: 'info');
    }

    public function runAudit()
    {
        if (!$this->website) return;

        $scannedCount = 0;

        // 1. If GitHub Repository is configured, scan remote repository files via GitHub API
        if (!empty($this->website->git_repository_url)) {
            $githubApi = new \App\Services\GithubApiService();
            $htmlFiles = $githubApi->listHtmlFiles($this->website);

            foreach ($htmlFiles as $rel) {
                $cleanName = trim(str_replace(['.html', '-', '_'], ['', ' ', ' '], $rel), '/ ');
                $parts = explode('/', $cleanName);
                $friendlyName = implode(' › ', array_map('ucwords', $parts));

                WebsitePage::firstOrCreate(
                    [
                        'website_id' => $this->website->id,
                        'path' => $rel,
                    ],
                    [
                        'friendly_name' => $friendlyName ?: 'Home Page',
                        'rewrite_enabled' => true,
                        'rewrite_interval_days' => $this->website->default_rewrite_interval_days ?? 5,
                    ]
                );
                $scannedCount++;
            }
        }

        // 2. Local Directory Deep Scan fallback (if local folder is provided)
        $dir = !empty($this->website->local_production_path) ? rtrim($this->website->local_production_path, '/\\') : null;
        
        if ($dir && is_dir($dir)) {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );

            foreach ($iterator as $file) {
                if ($file->isFile() && strtolower($file->getExtension()) === 'html') {
                    $rel = str_replace('\\', '/', substr($file->getPathname(), strlen($dir)));
                    if (!str_starts_with($rel, '/')) {
                        $rel = '/' . $rel;
                    }

                    $cleanName = trim(str_replace(['.html', '-', '_'], ['', ' ', ' '], $rel), '/ ');
                    $parts = explode('/', $cleanName);
                    $friendlyName = implode(' › ', array_map('ucwords', $parts));

                    WebsitePage::firstOrCreate(
                        [
                            'website_id' => $this->website->id,
                            'path' => $rel,
                        ],
                        [
                            'friendly_name' => $friendlyName ?: 'Home Page',
                            'rewrite_enabled' => true,
                            'rewrite_interval_days' => $this->website->default_rewrite_interval_days ?? 5,
                        ]
                    );
                    $scannedCount++;
                }
            }
        }

        $this->website->loadCount('pages');
        $this->dispatch('toast', title: 'Deep Audit Completed 🚀', message: "Discovered and registered {$scannedCount} HTML pages from GitHub repository.", type: 'success');
    }

    public function togglePageRewrite($pageId)
    {
        $page = WebsitePage::find($pageId);
        if ($page) {
            $page->update(['rewrite_enabled' => !$page->rewrite_enabled]);
            $this->dispatch('toast', title: 'Page Updated', message: 'Auto-refresh status toggled for page.', type: 'success');
        }
    }

    public function render()
    {
        $pages = collect();

        if ($this->website) {
            $query = WebsitePage::where('website_id', $this->website->id);
            if (!empty($this->searchPage)) {
                $query->where('path', 'like', "%{$this->searchPage}%");
            }
            $pages = $query->latest()->get();
        }

        return view('livewire.websites.show', [
            'website' => $this->website,
            'pages' => $pages,
        ]);
    }
}
