<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between border-b border-[#EAECF0] pb-4">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('websites.index') }}" class="text-xs font-semibold text-[#15803D] hover:text-[#15803D] flex items-center gap-1">
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
                        class="w-full px-3.5 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-[#F9FAFB] focus:bg-white text-[#101828] focus:ring-2 focus:ring-green-500/20 focus:border-[#22C55E] transition-all"
                    >
                    @error('name') <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#344054] mb-1">Primary Domain / Hostname *</label>
                    <input
                        wire:model="domain"
                        type="text"
                        placeholder="e.g. localhost/mysite or example.com"
                        class="w-full px-3.5 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-[#F9FAFB] focus:bg-white text-[#101828] focus:ring-2 focus:ring-green-500/20 focus:border-[#22C55E] transition-all"
                    >
                    @error('domain') <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- GitHub Repository Connection Details -->
            <div class="space-y-4 p-5 bg-[#F8FAFC] rounded-2xl border border-[#E2E8F0]">
                <div class="flex items-center gap-2 pb-2 border-b border-[#E2E8F0]">
                    <i class="fa-brands fa-github text-lg text-[#0F172A]"></i>
                    <div>
                        <h4 class="text-xs font-bold text-[#0F172A]">GitHub Cloud Repository Integration</h4>
                        <p class="text-[11px] text-[#64748B]">Autoflow will directly fetch files, perform AI rewrites, and commit via GitHub API (Triggers instant Vercel / Netlify live deployment)</p>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#334155] mb-1">GitHub Repository URL (or owner/repo) *</label>
                    <input
                        wire:model="git_repository_url"
                        type="text"
                        placeholder="https://github.com/imon-mahmud/catharsisintl or imon-mahmud/catharsisintl"
                        class="w-full px-3.5 py-2.5 text-xs font-mono rounded-xl border border-[#CBD5E1] bg-white text-[#0F172A] focus:ring-2 focus:ring-[#22C55E]"
                    >
                    @error('git_repository_url') <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#334155] mb-1">Target Git Branch *</label>
                        <input
                            wire:model="git_branch"
                            type="text"
                            placeholder="main"
                            class="w-full px-3.5 py-2.5 text-xs font-mono rounded-xl border border-[#CBD5E1] bg-white text-[#0F172A] focus:ring-2 focus:ring-[#22C55E]"
                        >
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#334155] mb-1">GitHub Personal Access Token (PAT) *</label>
                        <input
                            wire:model="git_access_token"
                            type="password"
                            placeholder="ghp_••••••••••••••••••••"
                            class="w-full px-3.5 py-2.5 text-xs font-mono rounded-xl border border-[#CBD5E1] bg-white text-[#0F172A] focus:ring-2 focus:ring-[#22C55E]"
                        >
                        <span class="text-[10px] text-[#64748B] mt-0.5 block">Generate from GitHub Settings ➜ Developer Settings ➜ Personal Access Tokens (repo permission)</span>
                    </div>
                </div>
            </div>

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
                    <i class="fa-solid fa-bolt text-xs"></i>
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
                        class="w-full px-3.5 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-[#F9FAFB] focus:bg-white text-[#101828] focus:ring-2 focus:ring-green-500/20 focus:border-[#22C55E] transition-all"
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
                            class="w-1/2 px-3.5 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-[#F9FAFB] focus:bg-white text-[#101828] focus:ring-2 focus:ring-green-500/20 focus:border-[#22C55E] transition-all"
                        >
                        <select
                            wire:model="interval_unit"
                            class="w-1/2 px-3.5 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-white text-[#101828] focus:ring-2 focus:ring-green-500/20 focus:border-[#22C55E] transition-all"
                        >
                            <option value="minutes">Minute(s)</option>
                            <option value="hours">Hour(s)</option>
                            <option value="days">Day(s)</option>
                            <option value="months">Month(s)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Website-wise Notification Receiver Email -->
            <div>
                <label class="block text-xs font-semibold text-[#344054] mb-1">Notification Receiver Email (Website-Specific Alert Address)</label>
                <div class="relative">
                    <input
                        wire:model="notification_email"
                        type="email"
                        placeholder="e.g. client@catharsisintl.com or admin@ideomet.com"
                        class="w-full px-3.5 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-[#F9FAFB] focus:bg-white text-[#101828] focus:ring-2 focus:ring-green-500/20 focus:border-[#22C55E] transition-all"
                    >
                </div>
                <span class="text-[10px] text-[#667085] mt-0.5 block">Email address to receive automated execution logs, AI rewrite status, and Git push notifications for this website.</span>
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#344054] mb-1">Protected Brand Terms (Comma separated)</label>
                <input
                    wire:model="protected_terms"
                    type="text"
                    placeholder="e.g. Autoflow, Ideomet Technologies, ISO 9001:2015, RL-549, BAIRA"
                    class="w-full px-3.5 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-[#F9FAFB] focus:bg-white text-[#101828] focus:ring-2 focus:ring-green-500/20 focus:border-[#22C55E] transition-all"
                >
                <span class="text-[10px] text-[#667085] mt-0.5 block">Exact legal or brand terms that Groq AI must NEVER alter or rewrite.</span>
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#344054] mb-1">Global Excluded HTML Selectors</label>
                <input
                    wire:model="global_exclusion_selectors"
                    type="text"
                    placeholder="e.g. header, footer, nav, .cookie-banner, #privacy-modal, .no-ai-rewrite"
                    class="w-full px-3.5 py-2 text-xs font-mono rounded-xl border border-[#D0D5DD] bg-[#F9FAFB] focus:bg-white text-[#101828] focus:ring-2 focus:ring-green-500/20 focus:border-[#22C55E] transition-all"
                >
                <span class="text-[10px] text-[#667085] mt-0.5 block">CSS selectors of HTML blocks to completely skip from AI content rewriting.</span>
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
                class="px-5 py-2.5 rounded-xl bg-[#22C55E] hover:bg-[#16A34A] text-white font-semibold text-xs shadow-xs transition-colors"
            >
                Save & Connect Website
            </button>
        </div>
    </form>
</div>
