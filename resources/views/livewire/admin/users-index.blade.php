<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-2xl font-bold text-[#0F172A] tracking-tight">Super Admin User & Plan Management</h1>
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-[#0F172A] text-[#22C55E] border border-slate-700 tracking-wider">
                    <i class="fa-solid fa-crown text-amber-400 mr-1"></i>GOD MODE
                </span>
            </div>
            <p class="text-xs text-[#64748B] mt-1">Full control over all platform users, SaaS plans, quotas, and subscription approvals.</p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="openCreateUserModal" type="button" class="px-4 py-2.5 bg-[#22C55E] hover:bg-[#16A34A] text-white font-bold text-xs rounded-xl shadow-sm transition-all hover:scale-105 flex items-center gap-2">
                <i class="fa-solid fa-user-plus text-xs"></i>
                Add New User
            </button>
        </div>
    </div>

    <!-- Quick Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-5 bg-white rounded-2xl border border-[#E2E8F0] shadow-card flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-[#64748B] uppercase tracking-wider">Total Platform Users</p>
                <h3 class="text-2xl font-extrabold text-[#0F172A] mt-1">{{ $totalUsersCount }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-[#F0FDF4] flex items-center justify-center text-[#15803D]">
                <i class="fa-solid fa-users text-lg"></i>
            </div>
        </div>

        <div class="p-5 bg-white rounded-2xl border border-[#E2E8F0] shadow-card flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-[#64748B] uppercase tracking-wider">Super Administrators</p>
                <h3 class="text-2xl font-extrabold text-[#0F172A] mt-1">{{ $superAdminsCount }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-slate-900 flex items-center justify-center text-[#22C55E]">
                <i class="fa-solid fa-shield-halved text-lg"></i>
            </div>
        </div>

        <div class="p-5 bg-white rounded-2xl border border-[#E2E8F0] shadow-card flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-[#64748B] uppercase tracking-wider">Active Subscriptions</p>
                <h3 class="text-2xl font-extrabold text-[#0F172A] mt-1">{{ $activeSubscriptionsCount }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-[#DCFCE7] flex items-center justify-center text-[#15803D]">
                <i class="fa-solid fa-circle-check text-lg"></i>
            </div>
        </div>
    </div>

    <!-- Users Table & Filters -->
    <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-card overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-[#E2E8F0] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-[#F8FAFC]">
            <div class="flex items-center gap-2 flex-1 max-w-md">
                <div class="relative w-full">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input
                        wire:model.live.debounce.300ms="search"
                        type="text"
                        placeholder="Search by name or email..."
                        class="w-full pl-9 pr-8 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-white focus:ring-2 focus:ring-[#22C55E] focus:border-transparent"
                    >
                    @if(!empty($search))
                        <button wire:click="$set('search', '')" type="button" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fa-solid fa-circle-xmark text-xs"></i>
                        </button>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-3">
                <select wire:model.live="roleFilter" class="px-3 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-white text-[#344054]">
                    <option value="all">All Roles</option>
                    <option value="superadmin">Super Admin Only</option>
                    <option value="user">Standard Users Only</option>
                </select>

                <select wire:model.live="planFilter" class="px-3 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-white text-[#344054]">
                    <option value="all">All SaaS Plans</option>
                    <option value="starter">Starter Plan</option>
                    <option value="pro">Pro Agency Plan</option>
                    <option value="enterprise">Enterprise Plan</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#F8FAFC] border-b border-[#E2E8F0] text-[#64748B] font-bold uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5">User Identity</th>
                        <th class="px-6 py-3.5">Role</th>
                        <th class="px-6 py-3.5">SaaS Plan</th>
                        <th class="px-6 py-3.5">Websites Quota</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E8F0] font-medium text-[#334155]">
                    @forelse($users as $u)
                        <tr class="hover:bg-[#F8FAFC]/80 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-[#0F172A] text-white flex items-center justify-center font-bold text-xs">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-[#0F172A]">{{ $u->name }}</div>
                                        <div class="text-[#64748B] text-[11px] font-mono">{{ $u->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($u->isSuperAdmin())
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-[#0F172A] text-[#22C55E]">
                                        <i class="fa-solid fa-crown text-[9px] text-amber-400"></i>
                                        Super Admin
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-700">
                                        User
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-0.5 rounded-md font-bold text-[11px] uppercase {{ $u->plan === 'enterprise' ? 'bg-purple-100 text-purple-800' : ($u->plan === 'pro' ? 'bg-[#DCFCE7] text-[#15803D]' : ($u->plan === 'starter' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600')) }}">
                                        {{ $u->plan === 'none' ? 'NO PLAN' : $u->plan }}
                                    </span>

                                    <!-- Quick Plan Assign Dropdown for Super Admin -->
                                    <div x-data="{ openPlan: false }" class="relative inline-block text-left">
                                        <button
                                            @click="openPlan = !openPlan"
                                            @click.away="openPlan = false"
                                            type="button"
                                            class="p-1 rounded-md text-[#64748B] hover:text-[#0F172A] hover:bg-gray-200/70 text-[10px] transition-colors"
                                            title="Assign SaaS Plan Manually"
                                        >
                                            <i class="fa-solid fa-wand-magic-sparkles text-amber-500"></i>
                                        </button>

                                        <div
                                            x-show="openPlan"
                                            x-cloak
                                            class="absolute left-0 mt-1 w-44 bg-white rounded-xl shadow-xl border border-[#E2E8F0] py-1 z-30 space-y-1 text-xs"
                                        >
                                            <div class="px-3 py-1 text-[10px] font-bold text-[#94A3B8] uppercase tracking-wider">
                                                Assign Plan
                                            </div>
                                            <button
                                                wire:click="assignPlan({{ $u->id }}, 'starter')"
                                                @click="openPlan = false"
                                                type="button"
                                                class="w-full text-left px-3 py-1.5 hover:bg-[#F8FAFC] flex items-center justify-between text-xs text-[#0F172A] font-medium"
                                            >
                                                <span>Starter ($29)</span>
                                                <span class="text-[10px] text-gray-400">3 Sites</span>
                                            </button>
                                            <button
                                                wire:click="assignPlan({{ $u->id }}, 'pro')"
                                                @click="openPlan = false"
                                                type="button"
                                                class="w-full text-left px-3 py-1.5 hover:bg-[#F0FDF4] text-[#15803D] flex items-center justify-between text-xs font-bold"
                                            >
                                                <span>Pro Agency ($79)</span>
                                                <span class="text-[10px] text-[#22C55E]">25 Sites</span>
                                            </button>
                                            <button
                                                wire:click="assignPlan({{ $u->id }}, 'enterprise')"
                                                @click="openPlan = false"
                                                type="button"
                                                class="w-full text-left px-3 py-1.5 hover:bg-purple-50 text-purple-700 flex items-center justify-between text-xs font-bold"
                                            >
                                                <span>Enterprise ($199)</span>
                                                <span class="text-[10px] text-purple-400">Unlimited</span>
                                            </button>
                                            <div class="border-t border-gray-100 my-1"></div>
                                            <button
                                                wire:click="assignPlan({{ $u->id }}, 'none')"
                                                @click="openPlan = false"
                                                type="button"
                                                class="w-full text-left px-3 py-1.5 hover:bg-rose-50 text-rose-600 text-xs font-semibold"
                                            >
                                                Revoke Plan (Free)
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-[#0F172A]">{{ $u->websites_count }}</span> / {{ $u->websites_limit }} sites
                                <div class="text-[10px] text-[#64748B]">{{ number_format($u->monthly_rewrites_limit) }} rewrites/mo</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($u->plan_status === 'active')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center gap-1 w-max">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#22C55E]"></span> Active
                                    </span>
                                @elseif($u->plan_status === 'pending_approval')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200 flex items-center gap-1 w-max">
                                        Pending Approval
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200 flex items-center gap-1 w-max">
                                        {{ ucfirst($u->plan_status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if($u->plan_status !== 'active')
                                        <button
                                            wire:click="approveSubscription({{ $u->id }})"
                                            type="button"
                                            class="px-2.5 py-1 rounded-lg bg-[#22C55E] hover:bg-[#16A34A] text-white font-bold text-[11px] transition-all shadow-xs"
                                            title="Approve Subscription"
                                        >
                                            <i class="fa-solid fa-check mr-1"></i>Approve
                                        </button>
                                    @endif

                                    <button
                                        wire:click="editUser({{ $u->id }})"
                                        type="button"
                                        class="p-1.5 text-gray-500 hover:text-[#15803D] rounded-lg hover:bg-gray-100 transition-colors"
                                        title="Edit User & Limits"
                                    >
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </button>

                                    @if(!$u->isSuperAdmin())
                                        <button
                                            wire:click="deleteUser({{ $u->id }})"
                                            wire:confirm="Are you sure you want to permanently delete user {{ $u->name }}?"
                                            type="button"
                                            class="p-1.5 text-gray-500 hover:text-rose-600 rounded-lg hover:bg-gray-100 transition-colors"
                                            title="Remove User"
                                        >
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-[#64748B]">
                                <i class="fa-solid fa-users-slash text-2xl mb-2"></i>
                                <p>No users found matching your criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-4 border-t border-[#E2E8F0]">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- User Create / Edit Modal -->
    @if($showUserModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/60 backdrop-blur-sm p-4 animate-in fade-in duration-150">
            <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-5 shadow-2xl border border-[#E2E8F0]">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h3 class="text-base font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fa-solid fa-user-gear text-[#22C55E]"></i>
                        {{ $editingUserId ? 'Edit User Account & Quotas' : 'Add New Platform User' }}
                    </h3>
                    <button wire:click="$set('showUserModal', false)" type="button" class="text-gray-400 hover:text-gray-600 p-1">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-[#344054] mb-1">Full Name *</label>
                            <input type="text" wire:model="userName" class="w-full px-3 py-2 text-xs rounded-xl border border-[#D0D5DD] text-[#0F172A]">
                            @error('userName') <span class="text-[10px] text-rose-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[#344054] mb-1">Email Address *</label>
                            <input type="email" wire:model="userEmail" class="w-full px-3 py-2 text-xs rounded-xl border border-[#D0D5DD] text-[#0F172A]">
                            @error('userEmail') <span class="text-[10px] text-rose-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-[#344054] mb-1">User Role *</label>
                            <select wire:model="userRole" class="w-full px-3 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-white text-[#0F172A]">
                                <option value="user">Standard User</option>
                                <option value="superadmin">Super Administrator (God Mode)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[#344054] mb-1">Password {{ $editingUserId ? '(Leave blank to keep current)' : '*' }}</label>
                            <input type="password" wire:model="userPassword" placeholder="••••••••" class="w-full px-3 py-2 text-xs rounded-xl border border-[#D0D5DD] text-[#0F172A]">
                            @error('userPassword') <span class="text-[10px] text-rose-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2 border-t border-gray-100">
                        <div>
                            <label class="block text-xs font-semibold text-[#344054] mb-1">SaaS Plan *</label>
                            <select wire:model.live="userPlan" class="w-full px-3 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-white text-[#0F172A]">
                                <option value="starter">Starter Plan ($29/mo)</option>
                                <option value="pro">Pro Agency ($79/mo)</option>
                                <option value="enterprise">Enterprise ($199/mo)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[#344054] mb-1">Subscription Status *</label>
                            <select wire:model="userPlanStatus" class="w-full px-3 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-white text-[#0F172A]">
                                <option value="active">Active</option>
                                <option value="trialing">Trialing</option>
                                <option value="pending_approval">Pending Approval</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-[#344054] mb-1">Websites Limit</label>
                            <input type="number" wire:model="userWebsitesLimit" class="w-full px-3 py-2 text-xs rounded-xl border border-[#D0D5DD] text-[#0F172A]">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[#344054] mb-1">Monthly Rewrites Limit</label>
                            <input type="number" wire:model="userMonthlyRewritesLimit" class="w-full px-3 py-2 text-xs rounded-xl border border-[#D0D5DD] text-[#0F172A]">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                    <button wire:click="$set('showUserModal', false)" type="button" class="px-4 py-2 text-xs font-semibold text-[#667085] hover:bg-[#F9FAFB] rounded-xl">Cancel</button>
                    <button wire:click="saveUser" type="button" class="px-5 py-2.5 rounded-xl bg-[#22C55E] hover:bg-[#16A34A] text-white font-bold text-xs shadow-sm transition-all hover:scale-105">
                        {{ $editingUserId ? 'Update User' : 'Create User' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
