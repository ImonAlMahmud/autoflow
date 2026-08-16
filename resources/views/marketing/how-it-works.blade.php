<x-marketing-layout title="How It Works — Autoflow Pipeline by Ideomet Technologies">
    <section class="py-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
            
            <div class="text-center space-y-4">
                <span class="px-3.5 py-1 rounded-full bg-purple-950 text-purple-400 border border-purple-800/60 text-xs font-semibold">
                    4-Step Automation Architecture
                </span>
                <h1 class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight">
                    How Autoflow Works
                </h1>
                <p class="text-slate-400 text-base max-w-2xl mx-auto">
                    From raw HTML extraction to automated Git Remote Push in under 12 seconds.
                </p>
            </div>

            <!-- Pipeline Steps -->
            <div class="space-y-8">
                <!-- Step 1 -->
                <div class="p-8 rounded-3xl bg-slate-900/80 border border-slate-800 flex flex-col md:flex-row items-start gap-6">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white font-extrabold text-xl flex items-center justify-center flex-shrink-0">
                        1
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-xl font-bold text-white">Connect Website & Local Workspace Path</h3>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            Register target website URLs and local directory paths (e.g. `C:\xampp\htdocs\website`). Autoflow reads existing `.html` files directly from disk.
                        </p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="p-8 rounded-3xl bg-slate-900/80 border border-slate-800 flex flex-col md:flex-row items-start gap-6">
                    <div class="w-12 h-12 rounded-2xl bg-purple-600 text-white font-extrabold text-xl flex items-center justify-center flex-shrink-0">
                        2
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-xl font-bold text-white">Configure AI Rules & Schedule Intervals</h3>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            Select Groq Llama 3.3 70B or custom AI models. Set automatic rewrite schedules (e.g. every 30 minutes, 2 days, or manual triggers).
                        </p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="p-8 rounded-3xl bg-slate-900/80 border border-slate-800 flex flex-col md:flex-row items-start gap-6">
                    <div class="w-12 h-12 rounded-2xl bg-pink-600 text-white font-extrabold text-xl flex items-center justify-center flex-shrink-0">
                        3
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-xl font-bold text-white">Style-Preserved AI Content Generation</h3>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            Autoflow extracts text elements while preserving `<span style="...">` gradients, `<br>` breaks, and CSS classes intact.
                        </p>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="p-8 rounded-3xl bg-slate-900/80 border border-slate-800 flex flex-col md:flex-row items-start gap-6">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white font-extrabold text-xl flex items-center justify-center flex-shrink-0">
                        4
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-xl font-bold text-white">Automated Git Commit & Remote Push</h3>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            Autoflow commits the updated HTML file and executes `git push origin main` automatically, triggering live hosting re-deploys (Vercel, Netlify, Cpanel).
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </section>
</x-marketing-layout>
