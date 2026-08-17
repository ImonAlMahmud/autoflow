<?php

namespace App\Livewire\Admin;

use App\Models\Website;
use App\Models\WebsitePage;
use Livewire\Component;
use Livewire\WithPagination;

class AllPages extends Component
{
    use WithPagination;

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

    public function render()
    {
        $websites = Website::with('user')->get();
        $pagesQuery = WebsitePage::with(['website.user', 'aiModel.provider'])->latest();

        if (!empty($this->search)) {
            $pagesQuery->where(function ($q) {
                $q->where('path', 'like', "%{$this->search}%")
                  ->orWhere('friendly_name', 'like', "%{$this->search}%")
                  ->orWhereHas('website', function ($wq) {
                      $wq->where('name', 'like', "%{$this->search}%")
                         ->orWhere('domain', 'like', "%{$this->search}%")
                         ->orWhereHas('user', fn($uq) => $uq->where('name', 'like', "%{$this->search}%")->orWhere('email', 'like', "%{$this->search}%"));
                  });
            });
        }

        if ($this->websiteFilter !== 'all') {
            $pagesQuery->where('website_id', $this->websiteFilter);
        }

        $pages = $pagesQuery->paginate(20);

        return view('livewire.admin.all-pages', [
            'pages' => $pages,
            'websites' => $websites,
        ]);
    }
}
