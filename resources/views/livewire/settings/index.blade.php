<div class="space-y-6 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-[#101828]">System Settings & SMTP Email Gateway</h1>
            <p class="text-xs text-[#667085] mt-1">Configure global application settings, outgoing SMTP mail server, and automated event notifications.</p>
        </div>
        <button wire:click="saveSettings" type="button" class="px-5 py-2.5 bg-[#22C55E] hover:bg-[#16A34A] text-white font-semibold text-xs rounded-xl shadow-xs transition-all flex items-center gap-2">
            <i class="fa-solid fa-check text-xs"></i>
            Save All Settings
        </button>
    </div>

    <!-- Global GitHub Cloud Repository Integration Card -->
    <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-card p-6 space-y-6">
        <div class="flex items-center gap-3 border-b border-[#EAECF0] pb-4">
            <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center">
                <i class="fa-brands fa-github text-base text-[#22C55E]"></i>
            </div>
            <div>
                <h3 class="text-base font-bold text-[#101828]">Global GitHub Cloud Integration (One-Time Setup)</h3>
                <p class="text-xs text-[#667085]">Provide a single GitHub Personal Access Token (PAT) to power automated fetches and commits across all your websites automatically.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <!-- Global GitHub Token -->
            <div class="md:col-span-3">
                <label class="block text-xs font-semibold text-[#344054] mb-1.5">Default GitHub Personal Access Token (PAT)</label>
                <input type="password" wire:model="globalGithubToken" placeholder="ghp_••••••••••••••••••••••••••••••••••••••••" class="w-full px-3.5 py-2.5 text-xs font-mono rounded-xl border border-[#D0D5DD] focus:outline-none focus:ring-2 focus:ring-green-500/20">
                <span class="text-[11px] text-[#64748B] mt-1 block">When set here, you won't need to enter a token when adding individual websites. Generate once from GitHub Settings ➜ Developer Settings ➜ Personal Access Tokens.</span>
            </div>

            <!-- Default Author Name -->
            <div>
                <label class="block text-xs font-semibold text-[#344054] mb-1.5">Default Git Commit Author Name</label>
                <input type="text" wire:model="globalGithubAuthorName" placeholder="Autoflow AI" class="w-full px-3.5 py-2 text-xs rounded-xl border border-[#D0D5DD] focus:outline-none focus:ring-2 focus:ring-green-500/20">
            </div>

            <!-- Default Author Email -->
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-[#344054] mb-1.5">Default Git Commit Author Email</label>
                <input type="email" wire:model="globalGithubAuthorEmail" placeholder="bot@autoflow.ideomet.com" class="w-full px-3.5 py-2 text-xs rounded-xl border border-[#D0D5DD] focus:outline-none focus:ring-2 focus:ring-green-500/20">
            </div>
        </div>
    </div>

    <!-- SMTP Mail Configuration Card -->
    <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-card p-6 space-y-6">
        <div class="flex items-center gap-3 border-b border-[#EAECF0] pb-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#15803D] flex items-center justify-center">
                <i class="fa-solid fa-envelope text-xs"></i>
            </div>
            <div>
                <h3 class="text-base font-bold text-[#101828]">SMTP Outgoing Mail Server Setup</h3>
                <p class="text-xs text-[#667085]">Connect your SMTP provider (Gmail, Mailgun, SendGrid, Amazon SES) to send emails on system events.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Mail Host -->
            <div>
                <label class="block text-xs font-semibold text-[#344054] mb-1.5">SMTP Host</label>
                <input type="text" wire:model="mailHost" placeholder="e.g. smtp.gmail.com or smtp.mailgun.org" class="w-full px-3.5 py-2 text-xs rounded-xl border border-[#D0D5DD] focus:outline-none focus:ring-2 focus:ring-green-500/20">
            </div>

            <!-- Mail Port -->
            <div>
                <label class="block text-xs font-semibold text-[#344054] mb-1.5">SMTP Port</label>
                <input type="text" wire:model="mailPort" placeholder="587 or 465" class="w-full px-3.5 py-2 text-xs rounded-xl border border-[#D0D5DD] focus:outline-none focus:ring-2 focus:ring-green-500/20">
            </div>

            <!-- Mail Username -->
            <div>
                <label class="block text-xs font-semibold text-[#344054] mb-1.5">SMTP Username / Email</label>
                <input type="text" wire:model="mailUsername" placeholder="your-email@example.com" class="w-full px-3.5 py-2 text-xs rounded-xl border border-[#D0D5DD] focus:outline-none focus:ring-2 focus:ring-green-500/20">
            </div>

            <!-- Mail Password -->
            <div>
                <label class="block text-xs font-semibold text-[#344054] mb-1.5">SMTP Password / App Key</label>
                <input type="password" wire:model="mailPassword" placeholder="••••••••••••••••" class="w-full px-3.5 py-2 text-xs rounded-xl border border-[#D0D5DD] focus:outline-none focus:ring-2 focus:ring-green-500/20">
            </div>

            <!-- Encryption -->
            <div>
                <label class="block text-xs font-semibold text-[#344054] mb-1.5">Encryption Protocol</label>
                <select wire:model="mailEncryption" class="w-full px-3.5 py-2 text-xs rounded-xl border border-[#D0D5DD] focus:outline-none focus:ring-2 focus:ring-green-500/20 bg-white">
                    <option value="tls">TLS (Recommended - Port 587)</option>
                    <option value="ssl">SSL (Port 465)</option>
                    <option value="null">None (Plaintext)</option>
                </select>
            </div>

            <!-- Sender Name -->
            <div>
                <label class="block text-xs font-semibold text-[#344054] mb-1.5">Sender From Name</label>
                <input type="text" wire:model="mailFromName" placeholder="Autoflow System Alerts" class="w-full px-3.5 py-2 text-xs rounded-xl border border-[#D0D5DD] focus:outline-none focus:ring-2 focus:ring-green-500/20">
            </div>

            <!-- Sender Email Address -->
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-[#344054] mb-1.5">Sender From Email Address</label>
                <input type="email" wire:model="mailFromAddress" placeholder="notifications@yourdomain.com" class="w-full px-3.5 py-2 text-xs rounded-xl border border-[#D0D5DD] focus:outline-none focus:ring-2 focus:ring-green-500/20">
            </div>
        </div>

        <!-- Test Email Section -->
        <div class="p-4 bg-[#F9FAFB] rounded-xl border border-[#EAECF0] space-y-3">
            <label class="block text-xs font-semibold text-[#344054]">Send Test Email</label>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div class="md:col-span-3">
                    <input type="email" wire:model="testEmailRecipient" placeholder="Enter recipient email address..." class="w-full block px-3.5 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-white focus:outline-none focus:ring-2 focus:ring-green-500/20">
                </div>
                <div>
                    <button
                        wire:click="sendTestEmail"
                        wire:loading.attr="disabled"
                        wire:target="sendTestEmail"
                        type="button"
                        class="w-full px-4 py-2 bg-emerald-600 hover:bg-emerald-700 disabled:opacity-75 disabled:cursor-wait text-white font-semibold text-xs rounded-xl shadow-xs transition-all flex items-center justify-center gap-1.5"
                    >
                        <span wire:loading.remove wire:target="sendTestEmail" class="inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-paper-plane text-xs"></i>
                            Send Test Email
                        </span>
                        <span wire:loading wire:target="sendTestEmail" class="inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-spinner fa-spin text-xs"></i>
                            Sending...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Email Notifications Card -->
    <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-card p-6 space-y-6">
        <div class="flex items-center gap-3 border-b border-[#EAECF0] pb-4">
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                <i class="fa-solid fa-bell text-xs"></i>
            </div>
            <div>
                <h3 class="text-base font-bold text-[#101828]">Automated Event Email Alerts</h3>
                <p class="text-xs text-[#667085]">Select system actions and status updates that trigger email notifications.</p>
            </div>
        </div>

        <div class="space-y-4">
            <!-- Event 1: Pending Approval Required -->
            <label class="flex items-start gap-3.5 p-3.5 rounded-xl border border-[#EAECF0] hover:bg-[#F9FAFB] cursor-pointer transition-colors">
                <input type="checkbox" wire:model="notifyOnApprovalRequired" class="mt-0.5 w-4 h-4 rounded text-[#15803D] focus:ring-green-500/20 border-[#D0D5DD]">
                <div>
                    <span class="text-xs font-bold text-[#101828] block">Pending Human Review Alert</span>
                    <span class="text-[11px] text-[#667085]">Send email notification whenever an AI content rewrite requires manual review before Git commit.</span>
                </div>
            </label>

            <!-- Event 2: Job Execution Failed -->
            <label class="flex items-start gap-3.5 p-3.5 rounded-xl border border-[#EAECF0] hover:bg-[#F9FAFB] cursor-pointer transition-colors">
                <input type="checkbox" wire:model="notifyOnJobFailed" class="mt-0.5 w-4 h-4 rounded text-[#15803D] focus:ring-green-500/20 border-[#D0D5DD]">
                <div>
                    <span class="text-xs font-bold text-[#101828] block">Rewrite Job Execution Failure</span>
                    <span class="text-[11px] text-[#667085]">Send alert if an AI model API call, validation check, or HTML parsing step fails.</span>
                </div>
            </label>

            <!-- Event 3: Git Push Failure -->
            <label class="flex items-start gap-3.5 p-3.5 rounded-xl border border-[#EAECF0] hover:bg-[#F9FAFB] cursor-pointer transition-colors">
                <input type="checkbox" wire:model="notifyOnGitPushFailed" class="mt-0.5 w-4 h-4 rounded text-[#15803D] focus:ring-green-500/20 border-[#D0D5DD]">
                <div>
                    <span class="text-xs font-bold text-[#101828] block">Git Remote Sync / Push Conflict Error</span>
                    <span class="text-[11px] text-[#667085]">Send immediate alert if Git commit or push to remote GitHub repository encounters credentials or conflict errors.</span>
                </div>
            </label>

            <!-- Event 4: Content Rewrite Completed -->
            <label class="flex items-start gap-3.5 p-3.5 rounded-xl border border-[#EAECF0] hover:bg-[#F9FAFB] cursor-pointer transition-colors">
                <input type="checkbox" wire:model="notifyOnRewriteComplete" class="mt-0.5 w-4 h-4 rounded text-[#15803D] focus:ring-green-500/20 border-[#D0D5DD]">
                <div>
                    <span class="text-xs font-bold text-[#101828] block">Successful Content Refresh Summary</span>
                    <span class="text-[11px] text-[#667085]">Send email digest when an automated page rewrite is published and pushed to Git repository.</span>
                </div>
            </label>
        </div>
    </div>
</div>
