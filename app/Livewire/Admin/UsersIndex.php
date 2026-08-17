<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class UsersIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $roleFilter = 'all';
    public string $planFilter = 'all';

    // User Create / Edit Modal State
    public bool $showUserModal = false;
    public ?int $editingUserId = null;
    public string $userName = '';
    public string $userEmail = '';
    public string $userRole = 'user'; // superadmin, user
    public string $userPassword = '';
    public string $userPlan = 'starter'; // starter, pro, enterprise
    public string $userPlanStatus = 'active'; // active, trialing, pending_approval, suspended
    public int $userWebsitesLimit = 3;
    public int $userMonthlyRewritesLimit = 100;

    public function mount()
    {
        $user = auth()->user();
        if (!$user || !$user->isSuperAdmin()) {
            abort(403, 'Unauthorized. Super Admin privileges required.');
        }
    }

    public function openCreateUserModal()
    {
        $this->editingUserId = null;
        $this->userName = '';
        $this->userEmail = '';
        $this->userRole = 'user';
        $this->userPassword = '';
        $this->userPlan = 'starter';
        $this->userPlanStatus = 'active';
        $this->userWebsitesLimit = 3;
        $this->userMonthlyRewritesLimit = 100;
        $this->showUserModal = true;
    }

    public function editUser($id)
    {
        $user = User::find($id);
        if (!$user) return;

        $this->editingUserId = $user->id;
        $this->userName = $user->name;
        $this->userEmail = $user->email;
        $this->userRole = $user->role ?? 'user';
        $this->userPassword = '';
        $this->userPlan = $user->plan ?? 'starter';
        $this->userPlanStatus = $user->plan_status ?? 'active';
        $this->userWebsitesLimit = $user->websites_limit ?? 3;
        $this->userMonthlyRewritesLimit = $user->monthly_rewrites_limit ?? 100;
        $this->showUserModal = true;
    }

    public function saveUser()
    {
        if ($this->editingUserId) {
            $this->validate([
                'userName' => 'required|string|max:255',
                'userEmail' => 'required|email|max:255|unique:users,email,' . $this->editingUserId,
                'userRole' => 'required|in:superadmin,user',
                'userPlan' => 'required|in:starter,pro,enterprise',
                'userPlanStatus' => 'required|in:active,trialing,pending_approval,suspended',
                'userWebsitesLimit' => 'required|integer|min:1',
                'userMonthlyRewritesLimit' => 'required|integer|min:1',
            ]);

            $user = User::find($this->editingUserId);
            if ($user) {
                $data = [
                    'name' => $this->userName,
                    'email' => $this->userEmail,
                    'role' => $this->userRole,
                    'plan' => $this->userPlan,
                    'plan_status' => $this->userPlanStatus,
                    'websites_limit' => $this->userWebsitesLimit,
                    'monthly_rewrites_limit' => $this->userMonthlyRewritesLimit,
                ];

                if (!empty($this->userPassword)) {
                    $data['password'] = Hash::make($this->userPassword);
                }

                $user->update($data);
                $this->dispatch('toast', title: 'User Updated', message: "Account for {$user->name} updated successfully.", type: 'success');
            }
        } else {
            $this->validate([
                'userName' => 'required|string|max:255',
                'userEmail' => 'required|email|max:255|unique:users,email',
                'userPassword' => 'required|string|min:6',
                'userRole' => 'required|in:superadmin,user',
                'userPlan' => 'required|in:starter,pro,enterprise',
                'userPlanStatus' => 'required|in:active,trialing,pending_approval,suspended',
                'userWebsitesLimit' => 'required|integer|min:1',
                'userMonthlyRewritesLimit' => 'required|integer|min:1',
            ]);

            $user = User::create([
                'name' => $this->userName,
                'email' => $this->userEmail,
                'password' => Hash::make($this->userPassword),
                'role' => $this->userRole,
                'plan' => $this->userPlan,
                'plan_status' => $this->userPlanStatus,
                'websites_limit' => $this->userWebsitesLimit,
                'monthly_rewrites_limit' => $this->userMonthlyRewritesLimit,
            ]);

            $this->dispatch('toast', title: 'User Created', message: "New user {$user->name} has been added.", type: 'success');
        }

        $this->showUserModal = false;
        $this->editingUserId = null;
    }

    public function assignPlan(int $userId, string $plan)
    {
        $user = User::find($userId);
        if (!$user) return;

        $limits = match ($plan) {
            'pro' => ['websites' => 25, 'rewrites' => 99999],
            'enterprise' => ['websites' => 9999, 'rewrites' => 999999],
            'starter' => ['websites' => 3, 'rewrites' => 100],
            default => ['websites' => 0, 'rewrites' => 0],
        };

        $status = $plan === 'none' ? 'none' : 'active';

        $user->update([
            'plan' => $plan,
            'plan_status' => $status,
            'websites_limit' => $limits['websites'],
            'monthly_rewrites_limit' => $limits['rewrites'],
        ]);

        $badge = match ($plan) {
            'pro' => 'Pro Agency ($79/mo)',
            'enterprise' => 'Enterprise ($199/mo)',
            'starter' => 'Starter ($29/mo)',
            default => 'Free / Inactive',
        };

        $this->dispatch('toast', 
            title: 'Plan Assigned Manually! 👑', 
            message: "Assigned {$badge} to {$user->name} with {$limits['websites']} websites limit.", 
            type: 'success'
        );
    }

    public function approveSubscription($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->update([
                'plan_status' => 'active',
            ]);
            $this->dispatch('toast', title: 'Subscription Approved', message: "Subscription for {$user->name} is now ACTIVE.", type: 'success');
        }
    }

    public function suspendUser($id)
    {
        $user = User::find($id);
        if ($user && $user->email !== 'admin@autoflow.local') {
            $user->update([
                'plan_status' => 'suspended',
            ]);
            $this->dispatch('toast', title: 'User Suspended', message: "User {$user->name} has been suspended.", type: 'warning');
        }
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if ($user) {
            if ($user->email === 'admin@autoflow.local') {
                $this->dispatch('toast', title: 'Action Denied', message: 'Cannot delete the primary Super Admin account.', type: 'danger');
                return;
            }
            $name = $user->name;
            $user->delete();
            $this->dispatch('toast', title: 'User Deleted', message: "User account {$name} permanently removed.", type: 'info');
        }
    }

    public function updatedUserPlan($val)
    {
        if ($val === 'pro') {
            $this->userWebsitesLimit = 25;
            $this->userMonthlyRewritesLimit = 99999;
        } elseif ($val === 'enterprise') {
            $this->userWebsitesLimit = 9999;
            $this->userMonthlyRewritesLimit = 999999;
        } else {
            $this->userWebsitesLimit = 3;
            $this->userMonthlyRewritesLimit = 100;
        }
    }

    public function render()
    {
        $query = User::withCount(['websites']);

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%");
            });
        }

        if ($this->roleFilter !== 'all') {
            $query->where('role', $this->roleFilter);
        }

        if ($this->planFilter !== 'all') {
            $query->where('plan', $this->planFilter);
        }

        $users = $query->latest()->paginate(15);

        return view('livewire.admin.users-index', [
            'users' => $users,
            'totalUsersCount' => User::count(),
            'superAdminsCount' => User::where('role', 'superadmin')->count(),
            'activeSubscriptionsCount' => User::where('plan_status', 'active')->count(),
        ]);
    }
}
