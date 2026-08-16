<?php

namespace App\Livewire\Subscription;

use Livewire\Component;

class Index extends Component
{
    public function switchPlan(string $newPlan)
    {
        $user = auth()->user();
        if (!$user) return;

        $limits = match ($newPlan) {
            'pro' => ['websites' => 25, 'rewrites' => 99999],
            'enterprise' => ['websites' => 9999, 'rewrites' => 999999],
            default => ['websites' => 3, 'rewrites' => 100],
        };

        $user->update([
            'plan' => $newPlan,
            'websites_limit' => $limits['websites'],
            'monthly_rewrites_limit' => $limits['rewrites'],
        ]);

        $this->dispatch('toast', title: 'Plan Updated', message: "Your subscription has been switched to the {$newPlan} plan.", type: 'success');
    }

    public function render()
    {
        $user = auth()->user();
        $websiteCount = $user ? $user->websites()->count() : 0;

        return view('livewire.subscription.index', [
            'user' => $user,
            'websiteCount' => $websiteCount,
        ]);
    }
}
