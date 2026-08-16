<x-marketing-layout title="Contact Ideomet Technologies — Autoflow SaaS Support & Sales">
    <section class="py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <div class="text-center space-y-4">
                <span class="px-3.5 py-1 rounded-full bg-indigo-950 text-indigo-400 border border-indigo-800/60 text-xs font-semibold">
                    Ideomet Technologies Contact Center
                </span>
                <h1 class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight">
                    Get in Touch with Our Team
                </h1>
                <p class="text-slate-400 text-base max-w-xl mx-auto">
                    Have questions about Autoflow, enterprise licensing, or custom integrations? We're here to help.
                </p>
            </div>

            <!-- Contact Form Card -->
            <div class="p-8 sm:p-12 rounded-3xl bg-slate-900/90 border border-slate-800 shadow-2xl space-y-6">
                <form action="#" method="POST" class="space-y-6" @submit.prevent="alert('Thank you for reaching out to Ideomet Technologies! Our team will contact you shortly.')">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-xs font-semibold text-slate-300">Your Full Name</label>
                            <input type="text" required placeholder="e.g. Imon Mahmud" class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:outline-none focus:border-indigo-500">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-semibold text-slate-300">Work Email Address</label>
                            <input type="email" required placeholder="e.g. imon@ideomet.tech" class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:outline-none focus:border-indigo-500">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-slate-300">How many websites do you manage?</label>
                        <select class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:outline-none focus:border-indigo-500">
                            <option>1 - 5 Websites</option>
                            <option>5 - 20 Websites</option>
                            <option>20 - 50+ Enterprise Sites</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-slate-300">Message / Inquiry Details</label>
                        <textarea rows="4" required placeholder="Tell us about your automation goals or custom requirements..." class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:outline-none focus:border-indigo-500"></textarea>
                    </div>

                    <button type="submit" class="w-full py-4 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-extrabold text-xs shadow-lg transition-all">
                        Submit Enterprise Inquiry →
                    </button>
                </form>
            </div>

            <!-- Corporate Info Footer Box -->
            <div class="p-6 rounded-2xl bg-slate-950 border border-slate-800 text-center text-xs text-slate-400 space-y-1">
                <p class="font-bold text-white">Ideomet Technologies Limited</p>
                <p>Enterprise Software Development & AI Engineering Division</p>
                <p class="text-indigo-400 font-mono pt-1">support@ideomet.tech</p>
            </div>

        </div>
    </section>
</x-marketing-layout>
