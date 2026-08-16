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

        // Scan local folder or git repo if local path exists
        if (!empty($this->website->local_production_path) && is_dir($this->website->local_production_path)) {
            $files = glob(rtrim($this->website->local_production_path, '/\\') . '/*.html');
            foreach ($files as $file) {
                $relativePath = basename($file);
                WebsitePage::firstOrCreate(
                    [
                        'website_id' => $this->website->id,
                        'path' => '/' . $relativePath,
                    ],
                    [
                        'friendly_name' => ucfirst(str_replace(['.html', '-', '_'], ['', ' ', ' '], $relativePath)),
                        'rewrite_enabled' => true,
                        'rewrite_interval_days' => $this->website->default_rewrite_interval_days ?? 30,
                    ]
                );
            }
            $this->dispatch('toast', title: 'Folder Scanned', message: 'Discovered and registered local HTML pages.', type: 'success');
        } else {
            $this->dispatch('toast', title: 'Content Audit Dispatched', message: 'Scanning website pages...', type: 'info');
        }
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
