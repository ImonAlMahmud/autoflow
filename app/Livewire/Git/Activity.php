<?php

namespace App\Livewire\Git;

use App\Models\GitOperation;
use Livewire\Component;

class Activity extends Component
{
    public function syncAllRemotes()
    {
        $this->dispatch('toast', title: 'Git Sync Started', message: 'Fetching latest commits and refs from all active Git remotes.', type: 'info');
    }

    public function render()
    {
        $commits = GitOperation::with('website')->latest()->get();

        return view('livewire.git.activity', [
            'commits' => $commits,
        ]);
    }
}
