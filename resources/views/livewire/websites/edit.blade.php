<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between border-b border-[#EAECF0] pb-4">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('websites.show', $websiteId ?? 1) }}" class="text-xs font-semibold text-[#15803D] hover:text-[#15803D] flex items-center gap-1">
                    ← Back to Website Overview
                </a>
            </div>
            <h1 class="text-2xl font-bold text-[#101828] tracking-tight mt-1">Edit Website Settings</h1>
            <p class="text-xs text-[#667085]">Manage local paths, Git repository tokens, automation frequency, and protected terms</p>
        </div>
    </div>

    <!-- Main Form -->
    <form wire:submit.prevent="update" class="space-y-6">
        
        <!-- SECTION 1: Identity & Source Type -->
        <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-xs p-6 space-y-5">
            <div class="border-b border-[#EAECF0] pb-3">
                <h3 class="text-sm font-bold text-[#101828]">Website Identity & Source Location</h3>
                <p class="text-xs text-[#667085]">Configure whether files are edited in a local folder or pulled from Git</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-[#344054] mb-1">Website Name *</label>
                    <input
                        wire:model.blur="name"
                        value="{{ $name ?? '' }}"
                        type="text"
                        class="w-full px-3.5 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-[#F9FAFB] focus:bg-white text-[#101828] focus:ring-2 focus:ring-green-500/20 focus:border-[#22C55E] transition-all"
                    >
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#344054] mb-1">Primary Domain / Hostname *</label>
                    <input
                        wire:model.blur="domain"
                        value="{{ $domain ?? '' }}"
                        type="text"
                        class="w-full px-3.5 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-[#F9FAFB] focus:bg-white text-[#101828] focus:ring-2 focus:ring-green-500/20 focus:border-[#22C55E] transition-all"
                    >
                </div>
            </div>            <!-- GitHub Repository Connection Details -->
            <div class="space-y-4 p-5 bg-[#F8FAFC] rounded-2xl border border-[#E2E8F0]">
                <div class="flex items-center gap-2 pb-2 border-b border-[#E2E8F0]">
                    <i class="fa-brands fa-github text-lg text-[#0F172A]"></i>
                    <div>
                        <h4 class="text-xs font-bold text-[#0F172A]">GitHub Cloud Repository Integration</h4>
                        <p class="text-[11px] text-[#64748B]">Autoflow fetches files, performs AI rewrites, and commits via GitHub API (Triggers instant Vercel / Netlify live deployment)</p>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#334155] mb-1">GitHub Repository URL (or owner/repo) *</label>
                    <input
                        wire:model.blur="git_repository_url"
                        value="{{ $git_repository_url ?? '' }}"
                        type="text"
                        placeholder="https://github.com/username/repository or username/repository"
                        class="w-full px-3.5 py-2.5 text-xs font-mono rounded-xl border border-[#CBD5E1] bg-white text-[#0F172A] focus:ring-2 focus:ring-[#22C55E]"
                    >
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#334155] mb-1">Target Branch *</label>
                        <input
                            wire:model.blur="git_branch"
                            value="{{ $git_branch ?? 'main' }}"
                            type="text"
                            placeholder="main"
                            class="w-full px-3.5 py-2.5 text-xs font-mono rounded-xl border border-[#CBD5E1] bg-white text-[#0F172A] focus:ring-2 focus:ring-[#22C55E]"
                        >
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#334155] mb-1">GitHub Personal Access Token (PAT) *</label>
                        <input
                            wire:model.blur="git_access_token"
                            value="{{ $git_access_token ?? '' }}"
                            type="password"
                            placeholder="ghp_••••••••••••••••••••"
                            class="w-full px-3.5 py-2.5 text-xs font-mono rounded-xl border border-[#CBD5E1] bg-white text-[#0F172A] focus:ring-2 focus:ring-[#22C55E]"
                        >
                        <span class="text-[10px] text-[#64748B] mt-0.5 block">Generate from GitHub Settings ➜ Developer Settings ➜ Personal Access Tokens (repo permission)</span>
                    </div>
                </div>
            </div>
            
            <!-- Git Commit Author Identity -->
            <div class="p-4 bg-[#F9FAFB] rounded-xl border border-[#EAECF0] space-y-3">
                <div class="border-b border-[#EAECF0] pb-2">
                    <h4 class="text-xs font-bold text-[#101828]">Git Commit Author Identity (GitHub Attribution)</h4>
                    <p class="text-[11px] text-[#667085]">Used for git add, git commit, and git push commands across both Local & Remote sources.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#344054] mb-1">Git Commit Author Name *</label>
                        <input
                            wire:model.blur="git_author_name"
                            value="{{ $git_author_name ?? '' }}"
                            type="text"
                            placeholder="e.g. Imon Mahmud"
                            class="w-full px-3.5 py-2 text-xs font-medium rounded-xl border border-[#D0D5DD] bg-white text-[#101828]"
                        >
                        <span class="text-[10px] text-[#667085] mt-0.5 block">Name to display in Git commit logs on GitHub</span>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#344054] mb-1">Git Commit Author Email *</label>
                        <input
                            wire:model.blur="git_author_email"
                            value="{{ $git_author_email ?? '' }}"
                            type="email"
                            placeholder="e.g. imon.mahmud4@gmail.com"
                            class="w-full px-3.5 py-2 text-xs font-medium rounded-xl border border-[#D0D5DD] bg-white text-[#101828]"
                        >
                        <span class="text-[10px] text-[#667085] mt-0.5 block">Valid GitHub email to verify commit identity</span>
                    </div>
                </div>
            </div>

            <!-- Test Connection -->
            <div class="pt-2 flex items-center justify-between">
                <button wire:click="testConnection" type="button" class="px-3.5 py-2 rounded-xl border border-[#D0D5DD] bg-white hover:bg-[#F9FAFB] text-xs font-semibold text-[#344054] flex items-center gap-2 shadow-xs">
                    <i class="fa-solid fa-bolt text-xs"></i>
                    Test Source Path / Connection
                </button>
                @if($connectionResult ?? null)
                    <span class="text-xs text-emerald-700 bg-emerald-50 px-3 py-1 rounded-lg border border-emerald-200 font-medium">✓ {{ $connectionResult }}</span>
                @endif
            </div>
        </div>

        <!-- SECTION 2: Automation & Policy -->
        <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-xs p-6 space-y-5">
            <div class="border-b border-[#EAECF0] pb-3">
                <h3 class="text-sm font-bold text-[#101828]">Automation Policy & Governance</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-[#344054] mb-1">Approval Mode</label>
                    <select wire:model="approval_mode" class="w-full px-3.5 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-[#F9FAFB] focus:bg-white text-[#101828]">
                        <option value="automatic" {{ ($approval_mode ?? 'automatic') === 'automatic' ? 'selected' : '' }}>Automatic (Auto-update content when validation passes)</option>
                        <option value="manual" {{ ($approval_mode ?? 'automatic') === 'manual' ? 'selected' : '' }}>Manual Review (Require human approval before applying)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[#344054] mb-1">Default Rewrite Interval *</label>
                    <div class="flex items-center gap-2">
                        <input wire:model.blur="interval_value" value="{{ $interval_value ?? 30 }}" type="number" min="1" class="w-1/2 px-3.5 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-[#F9FAFB] focus:bg-white text-[#101828]">
                        <select wire:model="interval_unit" class="w-1/2 px-3.5 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-white text-[#101828]">
                            <option value="minutes" {{ ($interval_unit ?? 'days') === 'minutes' ? 'selected' : '' }}>Minute(s)</option>
                            <option value="hours" {{ ($interval_unit ?? 'days') === 'hours' ? 'selected' : '' }}>Hour(s)</option>
                            <option value="days" {{ ($interval_unit ?? 'days') === 'days' ? 'selected' : '' }}>Day(s)</option>
                            <option value="weeks" {{ ($interval_unit ?? 'days') === 'weeks' ? 'selected' : '' }}>Week(s)</option>
                            <option value="months" {{ ($interval_unit ?? 'days') === 'months' ? 'selected' : '' }}>Month(s)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#344054] mb-1">Notification Receiver Email (Website-Specific Alert Address)</label>
                <input wire:model.blur="notification_email" value="{{ $notification_email ?? '' }}" type="email" placeholder="e.g. client@catharsisintl.com or admin@ideomet.com" class="w-full px-3.5 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-[#F9FAFB] focus:bg-white text-[#101828]">
                <span class="text-[10px] text-[#667085] mt-0.5 block">Email address to receive automated execution logs, AI rewrite status, and Git push notifications for this website.</span>
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#344054] mb-1">Protected Brand Terms (Comma separated)</label>
                <input wire:model.blur="protected_terms" value="{{ $protected_terms ?? '' }}" type="text" placeholder="e.g. Autoflow, Ideomet Technologies, ISO 9001:2015, RL-549, BAIRA" class="w-full px-3.5 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-[#F9FAFB] focus:bg-white text-[#101828]">
                <span class="text-[10px] text-[#667085] mt-0.5 block">Exact legal or brand terms that Groq AI must NEVER alter or rewrite.</span>
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#344054] mb-1">Global Excluded HTML Selectors</label>
                <input wire:model.blur="global_exclusion_selectors" value="{{ $global_exclusion_selectors ?? '' }}" type="text" placeholder="e.g. header, footer, nav, .cookie-banner, #privacy-modal, .no-ai-rewrite" class="w-full px-3.5 py-2 text-xs font-mono rounded-xl border border-[#D0D5DD] bg-[#F9FAFB] focus:bg-white text-[#101828]">
                <span class="text-[10px] text-[#667085] mt-0.5 block">CSS selectors of HTML blocks to completely skip from AI content rewriting.</span>
            </div>
        </div>

        <!-- Action Footer -->
        <div class="flex items-center justify-between pt-4 border-t border-[#EAECF0]">
            <button wire:click="deleteWebsite" wire:confirm="Are you sure you want to disconnect this website?" type="button" class="px-4 py-2.5 rounded-xl border border-rose-200 bg-rose-50 hover:bg-rose-100 text-xs font-semibold text-rose-700 transition-colors">
                Disconnect Website
            </button>
            <div class="flex items-center gap-3">
                <a href="{{ route('websites.show', $websiteId ?? 1) }}" class="px-4 py-2.5 rounded-xl border border-[#D0D5DD] bg-white hover:bg-[#F9FAFB] text-xs font-semibold text-[#344054] transition-colors">Cancel</a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#22C55E] hover:bg-[#16A34A] active:bg-[#15803D] text-white font-semibold text-xs shadow-xs transition-colors">Save Changes</button>
            </div>
        </div>
    </form>
</div>
