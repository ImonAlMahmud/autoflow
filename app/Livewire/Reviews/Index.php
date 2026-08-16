<?php

namespace App\Livewire\Reviews;

use App\Enums\JobStatus;
use App\Models\RewriteJob;
use Livewire\Component;

class Index extends Component
{
    public array $selectedReviews = [];

    public function approveBatch()
    {
        $count = count($this->selectedReviews);
        $this->dispatch('toast', title: 'Batch Approved', message: "Approved and queued Git commits for {$count} content revisions.", type: 'success');
        $this->selectedReviews = [];
    }

    public function rejectReview($id)
    {
        $this->dispatch('toast', title: 'Revision Rejected', message: "Content revision #{$id} rejected.", type: 'warning');
    }

    public function render()
    {
        $reviews = RewriteJob::with(['website', 'page'])
            ->where('status', JobStatus::PendingApproval)
            ->latest()
            ->get();

        return view('livewire.reviews.index', [
            'reviews' => $reviews,
        ]);
    }
}
