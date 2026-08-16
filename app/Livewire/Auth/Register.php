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
        $this->validate();

        $limits = match ($this->plan) {
            'pro' => ['websites' => 25, 'rewrites' => 99999],
            'enterprise' => ['websites' => 9999, 'rewrites' => 999999],
            default => ['websites' => 3, 'rewrites' => 100],
        };

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'plan' => $this->plan,
            'plan_status' => 'active',
            'websites_limit' => $limits['websites'],
            'monthly_rewrites_limit' => $limits['rewrites'],
        ]);

        Auth::login($user);

        session()->flash('toast', [
            'title' => 'Welcome to Autoflow!',
            'message' => "Your account has been created on the {$this->plan} plan.",
            'type' => 'success',
        ]);

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('auth.register')->layout('layouts.marketing');
    }
}
