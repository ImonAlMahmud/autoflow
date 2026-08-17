<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Register extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $plan = 'pro'; // starter, pro, enterprise

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'plan' => 'required|in:starter,pro,enterprise',
        ];
    }

    public function register()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => 'user',
            'plan' => 'none',
            'plan_status' => 'none',
            'websites_limit' => 0,
            'monthly_rewrites_limit' => 0,
        ]);

        Auth::login($user);

        session()->flash('toast', [
            'title' => 'Account Created! 🎉',
            'message' => 'Welcome to Autoflow! Please select a subscription plan to unlock full automation features.',
            'type' => 'success',
        ]);

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('auth.register')->layout('components.marketing-layout');
    }
}
