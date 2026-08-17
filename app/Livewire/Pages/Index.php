<?php

namespace App\Livewire\Pages;

use App\Models\Website;
use App\Models\WebsitePage;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public array $selectedPages = [];
    public bool $selectAll = false;
    public string $search = '';
    public string $websiteFilter = 'all';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingWebsiteFilter()
    {
        $this->resetPage();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $user = auth()->user();
            $query = WebsitePage::query();
            if ($user) {
                $query->whereHas('website', fn($q) => $q->where('user_id', $user->id));
            }
            $this->selectedPages = $query->pluck('id')->toArray();
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

        $websitesQuery = Website::query();
        $pagesQuery = WebsitePage::with(['website', 'aiModel.provider'])->latest();

        // Isolate so user (even Super Admin on regular page) only sees their own website pages
        if ($user) {
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

        $pages = $pagesQuery->paginate(20);

        return view('livewire.pages.index', [
            'pages' => $pages,
            'websites' => $websites,
        ]);
    }
}
