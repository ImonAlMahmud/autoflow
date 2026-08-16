<?php

namespace App\Livewire\Websites;

use App\Enums\WebsiteStatus;
use App\Models\Website;
use Livewire\Component;

class Index extends Component
{
    public string $search = '';
    public string $statusFilter = 'all';
    public bool $showConnectModal = false;

    public function triggerSync($id)
    {
        $this->dispatch('toast', title: 'Git Sync Started', message: "Fetching latest repository refs for website #{$id}.", type: 'info');
    }

    public function toggleStatus($id)
    {
        $website = Website::find($id);
        if ($website) {
            $newStatus = $website->status === WebsiteStatus::Active ? WebsiteStatus::Paused : WebsiteStatus::Active;
            $website->update(['status' => $newStatus]);
            $this->dispatch('toast', title: 'Status Updated', message: "Website status set to {$newStatus->value}.", type: 'success');
        }
    }

    public function render()
    {
        $query = Website::withCount('pages');

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('domain', 'like', "%{$this->search}%")
                  ->orWhere('git_repository_url', 'like', "%{$this->search}%");
            });
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        $websites = $query->latest()->get();

        return view('livewire.websites.index', [
            'websites' => $websites,
        ]);
    }
}
