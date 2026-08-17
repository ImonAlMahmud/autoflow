<?php

namespace App\Livewire\Pages;

use App\Models\Website;
use App\Models\WebsitePage;
use Livewire\Component;

class Index extends Component
{
    public array $selectedPages = [];
    public bool $selectAll = false;
    public string $search = '';
    public string $websiteFilter = 'all';

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedPages = WebsitePage::pluck('id')->toArray();
        } else {
            $this->selectedPages = [];
        }
    }

    public function triggerBatchRewrite()
    {
        $count = count($this->selectedPages);
        $this->dispatch('toast', title: 'Batch Jobs Queued', message: "Queued AI rewrite jobs for {$count} selected pages.", type: 'success');
        $this->selectedPages = [];
        $this->selectAll = false;
    }

    public function triggerRewrite($id)
    {
        $this->dispatch('toast', title: 'Job Dispatched', message: "Queued AI content refresh job for page #{$id}.", type: 'success');
    }

    public function render()
    {
        $user = auth()->user();
        $isSuper = $user && $user->isSuperAdmin();

        $websitesQuery = Website::query();
        $pagesQuery = WebsitePage::with('website')->latest();

        if (!$isSuper && $user) {
            $websitesQuery->where('user_id', $user->id);
            $pagesQuery->whereHas('website', fn($q) => $q->where('user_id', $user->id));
        }

        $websites = $websitesQuery->get();

        if (!empty($this->search)) {
            $pagesQuery->where(function($q) {
                $q->where('path', 'like', "%{$this->search}%")
                  ->orWhere('friendly_name', 'like', "%{$this->search}%");
            });
        }

        if ($this->websiteFilter !== 'all') {
            $pagesQuery->where('website_id', $this->websiteFilter);
        }

        $pages = $pagesQuery->get();

        return view('livewire.pages.index', [
            'pages' => $pages,
            'websites' => $websites,
        ]);
    }
}
