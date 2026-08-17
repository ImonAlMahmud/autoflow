<?php

namespace App\Livewire\Admin;

use App\Models\RewriteJob;
use App\Models\Website;
use Livewire\Component;
use Livewire\WithPagination;

class AllWebsites extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = 'all';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Website::with(['user', 'pages'])
            ->withCount('pages');

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('domain', 'like', "%{$this->search}%")
                  ->orWhere('git_repository_url', 'like', "%{$this->search}%")
                  ->orWhereHas('user', function ($uq) {
                      $uq->where('name', 'like', "%{$this->search}%")
                         ->orWhere('email', 'like', "%{$this->search}%");
                  });
            });
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        $websites = $query->latest()->paginate(20);

        // Attach last job history to each website
        $websites->getCollection()->transform(function ($site) {
            $site->last_job = RewriteJob::where('website_id', $site->id)
                ->latest()
                ->first();
            return $site;
        });

        return view('livewire.admin.all-websites', [
            'websites' => $websites,
        ]);
    }
}
