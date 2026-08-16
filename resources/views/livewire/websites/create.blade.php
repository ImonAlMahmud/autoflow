<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between border-b border-[#EAECF0] pb-4">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('websites.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 flex items-center gap-1">
                    ← Back to Websites
                </a>
            </div>
            <h1 class="text-2xl font-bold text-[#101828] tracking-tight mt-1">Connect New Website</h1>
            <p class="text-xs text-[#667085]">Add a local computer website folder or link a remote Git repository for automated AI content updates</p>
        </div>
    </div>

    <!-- Main Card Form -->
    <form wire:submit.prevent="save" class="space-y-6">
        
        <!-- SECTION 1: Website Details & Source Selection -->
        <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-xs p-6 space-y-5">
            <div class="border-b border-[#EAECF0] pb-3">
                <h3 class="text-sm font-bold text-[#101828]">Website Identity & Source Type</h3>
                <p class="text-xs text-[#667085]">Choose whether to use an existing local folder or pull from a Git repository</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-[#344054] mb-1">Website Name *</label>
                    <input
                        wire:model="name"
                        type="text"
                        placeholder="e.g. My Local Company Site"
                        class="w-full px-3.5 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-[#F9FAFB] focus:bg-white text-[#101828] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all"
                    >
                    @error('name') <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#344054] mb-1">Primary Domain / Hostname *</label>
                    <input
                        wire:model="domain"
                        type="text"
                        placeholder="e.g. localhost/mysite or example.com"
                        class="w-full px-3.5 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-[#F9FAFB] focus:bg-white text-[#101828] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all"
                    >
                    @error('domain') <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Source Selection Cards (Local Folder vs Git Remote) -->
            <div>
                <label class="block text-xs font-semibold text-[#344054] mb-2">Website Files Source Location *</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Option A: Local Computer Folder -->
                    <label class="p-4 rounded-xl border-2 cursor-pointer transition-all flex items-start gap-3 {{ $source_type === 'local' ? 'border-indigo-600 bg-indigo-50/50' : 'border-[#EAECF0] hover:bg-[#F9FAFB]' }}">
                        <input type="radio" wire:model.live="source_type" value="local" class="mt-1 text-indigo-600 focus:ring-indigo-500">
                        <div>
                            <span class="text-xs font-bold text-[#101828] block">🖥️ Local Computer Folder (Direct)</span>
                            <span class="text-[11px] text-[#667085] mt-0.5 block">Use an existing static website folder already located on this PC (No Git Pull needed).</span>
                        </div>
                    </label>

                    <!-- Option B: GitHub / Remote Repository -->
                    <label class="p-4 rounded-xl border-2 cursor-pointer transition-all flex items-start gap-3 {{ $source_type === 'git' ? 'border-indigo-600 bg-indigo-50/50' : 'border-[#EAECF0] hover:bg-[#F9FAFB]' }}">
                        <input type="radio" wire:model.live="source_type" value="git" class="mt-1 text-indigo-600 focus:ring-indigo-500">
                        <div>
                            <span class="text-xs font-bold text-[#101828] block">🌐 GitHub / Remote Git Repository</span>
                            <span class="text-[11px] text-[#667085] mt-0.5 block">Automatically clone and pull latest files from GitHub/GitLab before rewriting.</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Dynamic Input Fields depending on Source -->
            @if($source_type === 'local')
                <div class="p-4 bg-[#F9FAFB] rounded-xl border border-[#EAECF0] space-y-2">
                    <label class="block text-xs font-semibold text-[#344054]">Local Website Directory Path *</label>
                    <input
                        wire:model="local_production_path"
                        type="text"
                        placeholder="e.g. C:\xampp\htdocs\mysite or C:\Users\YourName\Projects\my-website"
                        class="w-full px-3.5 py-2 text-xs font-mono rounded-xl border border-[#D0D5DD] bg-white text-[#101828] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600"
                    >
                    <p class="text-[11px] text-[#667085]">Autoflow will scan and edit HTML files directly inside this local folder.</p>
                    @error('local_production_path') <span class="text-rose-600 text-[11px] block">{{ $message }}</span> @enderror
                </div>
            @else
                <div class="space-y-4 p-4 bg-[#F9FAFB] rounded-xl border border-[#EAECF0]">
                    <div>
                        <label class="block text-xs font-semibold text-[#344054] mb-1">Git Repository URL (HTTPS or SSH) *</label>
                        <input
                            wire:model="git_repository_url"
                            type="text"
                            placeholder="https://github.com/username/repository.git"
                            class="w-full px-3.5 py-2 text-xs font-mono rounded-xl border border-[#D0D5DD] bg-white text-[#101828] focus:ring-2 focus:ring-indigo-500/20"
                        >
                        @error('git_repository_url') <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-[#344054] mb-1">Target Branch</label>
                            <input
                                wire:model="git_branch"
                                type="text"
                                placeholder="main"
                                class="w-full px-3.5 py-2 text-xs font-mono rounded-xl border border-[#D0D5DD] bg-white"
                            >
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#344054] mb-1">Git Personal Access Token (PAT)</label>
                            <input
                                wire:model="git_access_token"
                                type="password"
                                placeholder="ghp_••••••••••••••••••••"
                                class="w-full px-3.5 py-2 text-xs font-mono rounded-xl border border-[#D0D5DD] bg-white"
                            >
                        </div>
                    </div>
                </div>
            @endif

            <!-- ALWAYS VISIBLE: Git Commit Author Configuration for all source types -->
            <div class="p-4 bg-[#F9FAFB] rounded-xl border border-[#EAECF0] space-y-3">
                <div class="border-b border-[#EAECF0] pb-2">
                    <h4 class="text-xs font-bold text-[#101828]">Git Commit Author Identity (GitHub Attribution)</h4>
                    <p class="text-[11px] text-[#667085]">Used for git add, git commit, and git push commands across both Local & Remote sources.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#344054] mb-1">Git Commit Author Name *</label>
                        <input
                            wire:model="git_author_name"
                            type="text"
                            placeholder="e.g. Imon Mahmud"
                            class="w-full px-3.5 py-2 text-xs font-medium rounded-xl border border-[#D0D5DD] bg-white text-[#101828]"
                        >
                        <span class="text-[10px] text-[#667085] mt-0.5 block">Name to display in Git commit logs on GitHub</span>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#344054] mb-1">Git Commit Author Email *</label>
                        <input
                            wire:model="git_author_email"
                            type="email"
                            placeholder="e.g. imon.mahmud4@gmail.com"
                            class="w-full px-3.5 py-2 text-xs font-medium rounded-xl border border-[#D0D5DD] bg-white text-[#101828]"
                        >
                        <span class="text-[10px] text-[#667085] mt-0.5 block">Valid GitHub email to verify commit identity</span>
                    </div>
                </div>
            </div>

            <!-- Test Handshake / Verification Button -->
            <div class="pt-2 flex items-center justify-between">
                <button
                    wire:click="testConnection"
                    type="button"
                    class="px-3.5 py-2 rounded-xl border border-[#D0D5DD] bg-white hover:bg-[#F9FAFB] text-xs font-semibold text-[#344054] transition-colors flex items-center gap-2 shadow-xs"
                >
                    <svg class="w-4 h-4 text-indigo-600 {{ $testingConnection ? 'animate-spin' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Test Source Path / Connection
                </button>

                @if($connectionResult)
                    <span class="text-xs text-emerald-700 bg-emerald-50 px-3 py-1 rounded-lg border border-emerald-200 font-medium">
                        ✓ {{ $connectionResult }}
                    </span>
                @endif
            </div>
        </div>

        <!-- SECTION 2: Automation & Rewrite Policy -->
        <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-xs p-6 space-y-5">
            <div class="border-b border-[#EAECF0] pb-3">
                <h3 class="text-sm font-bold text-[#101828]">Automation Policy & Governance</h3>
                <p class="text-xs text-[#667085]">Configure approval mode, rewrite frequency, and protected terms</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-[#344054] mb-1">Approval Mode</label>
                    <select
                        wire:model="approval_mode"
                        class="w-full px-3.5 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-[#F9FAFB] focus:bg-white text-[#101828] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all"
                    >
                        <option value="automatic">Automatic (Auto-update content when validation passes)</option>
                        <option value="manual">Manual Review (Require human approval before applying)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#344054] mb-1">Default Rewrite Interval *</label>
                    <div class="flex items-center gap-2">
                        <input
                            wire:model="interval_value"
                            type="number"
                            min="1"
                            class="w-1/2 px-3.5 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-[#F9FAFB] focus:bg-white text-[#101828] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all"
                        >
                        <select
                            wire:model="interval_unit"
                            class="w-1/2 px-3.5 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-white text-[#101828] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all"
                        >
                            <option value="minutes">Minute(s)</option>
                            <option value="hours">Hour(s)</option>
                            <option value="days">Day(s)</option>
                            <option value="months">Month(s)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#344054] mb-1">Protected Brand Terms (Comma separated)</label>
                <input
                    wire:model="protected_terms"
                    type="text"
                    placeholder="Autoflow, CompanyName, ProductKey"
                    class="w-full px-3.5 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-[#F9FAFB] focus:bg-white text-[#101828] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all"
                >
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#344054] mb-1">Global Excluded HTML Selectors</label>
                <input
                    wire:model="global_exclusion_selectors"
                    type="text"
                    placeholder="header, footer, nav, .sidebar"
                    class="w-full px-3.5 py-2 text-xs font-mono rounded-xl border border-[#D0D5DD] bg-[#F9FAFB] focus:bg-white text-[#101828] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all"
                >
            </div>
        </div>

        <!-- Form Actions Bar -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a
                href="{{ route('websites.index') }}"
                class="px-4 py-2.5 rounded-xl border border-[#D0D5DD] bg-white text-xs font-semibold text-[#344054] hover:bg-[#F9FAFB] transition-colors"
            >
                Cancel
            </a>
            <button
                type="submit"
                class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-xs transition-colors"
            >
                Save & Connect Website
            </button>
        </div>
    </form>
</div>
