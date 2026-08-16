<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between border-b border-[#EAECF0] pb-4">
        <div>
            <h1 class="text-2xl font-bold text-[#101828] tracking-tight">Admin Profile & Security</h1>
            <p class="text-xs text-[#667085]">Manage personal information, authentication password, and active sessions</p>
        </div>
    </div>

    <!-- Profile Info Card -->
    <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-xs p-6 space-y-5">
        <h3 class="text-sm font-bold text-[#101828] border-b border-[#EAECF0] pb-3">Personal Details</h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-[#344054] mb-1">Full Name</label>
                <input
                    wire:model="name"
                    type="text"
                    class="w-full px-3.5 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-[#F9FAFB] text-[#101828]"
                >
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#344054] mb-1">Email Address</label>
                <input
                    wire:model="email"
                    type="email"
                    class="w-full px-3.5 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-[#F9FAFB] text-[#101828]"
                >
            </div>
        </div>

        <div class="flex justify-end">
            <button
                wire:click="updateProfile"
                type="button"
                class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-xs transition-colors"
            >
                Update Profile Info
            </button>
        </div>
    </div>

    <!-- Password Card -->
    <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-xs p-6 space-y-5">
        <h3 class="text-sm font-bold text-[#101828] border-b border-[#EAECF0] pb-3">Change Password</h3>

        <div class="space-y-4 max-w-md">
            <div>
                <label class="block text-xs font-semibold text-[#344054] mb-1">Current Password</label>
                <input
                    wire:model="currentPassword"
                    type="password"
                    class="w-full px-3.5 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-[#F9FAFB] text-[#101828]"
                >
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#344054] mb-1">New Password</label>
                <input
                    wire:model="newPassword"
                    type="password"
                    class="w-full px-3.5 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-[#F9FAFB] text-[#101828]"
                >
            </div>
        </div>

        <div class="flex justify-end">
            <button
                wire:click="updatePassword"
                type="button"
                class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-xs transition-colors"
            >
                Update Password
            </button>
        </div>
    </div>
</div>
