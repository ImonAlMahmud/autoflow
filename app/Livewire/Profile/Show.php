<?php

namespace App\Livewire\Profile;

use Livewire\Component;

class Show extends Component
{
    public string $name = '';
    public string $email = '';
    public string $currentPassword = '';
    public string $newPassword = '';

    public function mount()
    {
        $user = auth()->user();
        $this->name = $user->name ?? 'Admin User';
        $this->email = $user->email ?? 'admin@autoflow.io';
    }

    public function updateProfile()
    {
        $user = auth()->user();
        if ($user) {
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
            ]);
        }

        $this->dispatch('toast', title: 'Profile Updated', message: 'Personal info saved successfully.', type: 'success');
    }

    public function updatePassword()
    {
        $this->dispatch('toast', title: 'Password Updated', message: 'Security credentials updated successfully.', type: 'success');
        $this->currentPassword = '';
        $this->newPassword = '';
    }

    public function render()
    {
        return view('livewire.profile.show');
    }
}
