<x-marketing-layout>

<!-- HERO + FORM (split layout) -->
<section class="relative overflow-hidden bg-gradient-to-b from-[#F0FDF4] via-white to-white pt-20 pb-16 sm:pt-28 sm:pb-20">
    <div class="absolute top-0 left-0 w-72 h-72 bg-[#DCFCE7] rounded-full blur-3xl opacity-40 -translate-x-1/3 -translate-y-1/3 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-14 items-start">

            <!-- Left: Info -->
            <div class="space-y-8 pt-4">
                <div data-reveal class="space-y-5">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#DCFCE7] border border-[#BBF7D0] text-[#15803D] text-xs font-bold tracking-wide shadow-xs">
                        <i class="fa-solid fa-headset text-[#15803D]"></i>Enterprise Support & Sales
                    </div>
                    <h1 class="text-4xl sm:text-5xl font-extrabold text-[#0F172A] tracking-tight leading-tight">
                        Get in touch with<br>the Autoflow team
                    </h1>
                    <p class="text-lg text-[#64748B] leading-relaxed">
                        Whether you're evaluating Autoflow for your agency, need enterprise pricing, or have a technical question — our team responds within 24 business hours.
                    </p>
                </div>

                <!-- Contact Info Cards -->
                <div data-reveal class="space-y-3">
                    <div class="flex items-center gap-4 p-4 rounded-2xl bg-white border border-[#E2E8F0] shadow-sm">
                        <div class="w-11 h-11 rounded-xl bg-[#DCFCE7] flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-envelope text-[#15803D]"></i>
                        </div>
                        <div>
                            <div class="text-xs text-[#64748B] font-semibold">Email Us</div>
                            <a href="mailto:autoflow@ideomet.com" class="text-sm font-bold text-[#0F172A] hover:text-[#15803D] transition-colors">autoflow@ideomet.com</a>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 p-4 rounded-2xl bg-white border border-[#E2E8F0] shadow-sm">
                        <div class="w-11 h-11 rounded-xl bg-[#DCFCE7] flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-location-dot text-[#15803D]"></i>
                        </div>
                        <div>
                            <div class="text-xs text-[#64748B] font-semibold">Headquarters</div>
                            <div class="text-sm font-bold text-[#0F172A]">Dhaka, Bangladesh</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 p-4 rounded-2xl bg-white border border-[#E2E8F0] shadow-sm">
                        <div class="w-11 h-11 rounded-xl bg-[#DCFCE7] flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-clock text-[#15803D]"></i>
                        </div>
                        <div>
                            <div class="text-xs text-[#64748B] font-semibold">Support Hours</div>
                            <div class="text-sm font-bold text-[#0F172A]">Mon–Fri, 9am–6pm (BST +6)</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 p-4 rounded-2xl bg-white border border-[#E2E8F0] shadow-sm">
                        <div class="w-11 h-11 rounded-xl bg-[#DCFCE7] flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-bolt text-[#15803D]"></i>
                        </div>
                        <div>
                            <div class="text-xs text-[#64748B] font-semibold">Response Time</div>
                            <div class="text-sm font-bold text-[#0F172A]">Within 24 hours on business days</div>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div data-reveal class="flex flex-wrap gap-3">
                    <a href="{{ route('pricing') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#F0FDF4] hover:bg-[#DCFCE7] text-[#15803D] text-xs font-bold transition-colors border border-[#DCFCE7]">
                        <i class="fa-solid fa-tag"></i>View Pricing
                    </a>
                    <a href="{{ route('how-it-works') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#F0FDF4] hover:bg-[#DCFCE7] text-[#15803D] text-xs font-bold transition-colors border border-[#DCFCE7]">
                        <i class="fa-solid fa-gears"></i>How It Works
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#22C55E] hover:bg-[#16A34A] text-white text-xs font-bold transition-colors shadow-xs">
                        <i class="fa-solid fa-rocket"></i>Start Free Trial
                    </a>
                </div>
            </div>

            <!-- Right: Contact Form -->
            <div data-reveal class="relative">
                <div class="absolute inset-0 bg-green-500/5 rounded-3xl blur-3xl"></div>
                <div class="relative bg-white border border-[#E2E8F0] rounded-3xl p-8 shadow-card space-y-6">
                    <div>
                        <h2 class="text-xl font-extrabold text-[#0F172A]"><i class="fa-solid fa-paper-plane text-[#22C55E] mr-2"></i>Send us a message</h2>
                        <p class="text-sm text-[#64748B] mt-1">We'll reply to your email within 24 hours.</p>
                    </div>

                    <form action="#" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-[#0F172A] mb-1.5"><i class="fa-solid fa-user mr-1 text-gray-400"></i>First Name *</label>
                                <input type="text" name="first_name" required placeholder="Imon" class="w-full px-4 py-3 text-sm rounded-xl border border-[#CBD5E1] bg-[#F8FAFC] text-[#0F172A] focus:bg-white focus:ring-2 focus:ring-green-500/20 focus:border-[#22C55E] transition-all outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-[#0F172A] mb-1.5"><i class="fa-solid fa-user mr-1 text-gray-400"></i>Last Name *</label>
                                <input type="text" name="last_name" required placeholder="Mahmud" class="w-full px-4 py-3 text-sm rounded-xl border border-[#CBD5E1] bg-[#F8FAFC] text-[#0F172A] focus:bg-white focus:ring-2 focus:ring-green-500/20 focus:border-[#22C55E] transition-all outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#0F172A] mb-1.5"><i class="fa-solid fa-envelope mr-1 text-gray-400"></i>Work Email *</label>
                            <input type="email" name="email" required placeholder="imon@company.com" class="w-full px-4 py-3 text-sm rounded-xl border border-[#CBD5E1] bg-[#F8FAFC] text-[#0F172A] focus:bg-white focus:ring-2 focus:ring-green-500/20 focus:border-[#22C55E] transition-all outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#0F172A] mb-1.5"><i class="fa-solid fa-building mr-1 text-gray-400"></i>Company / Agency</label>
                            <input type="text" name="company" placeholder="Agency Name" class="w-full px-4 py-3 text-sm rounded-xl border border-[#CBD5E1] bg-[#F8FAFC] text-[#0F172A] focus:bg-white focus:ring-2 focus:ring-green-500/20 focus:border-[#22C55E] transition-all outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#0F172A] mb-1.5"><i class="fa-solid fa-tag mr-1 text-gray-400"></i>Inquiry Type</label>
                            <select name="type" class="w-full px-4 py-3 text-sm rounded-xl border border-[#CBD5E1] bg-[#F8FAFC] text-[#0F172A] focus:bg-white focus:ring-2 focus:ring-green-500/20 focus:border-[#22C55E] transition-all outline-none">
                                <option value="sales">Sales & Custom Plan</option>
                                <option value="technical">Technical Question</option>
                                <option value="agency">Agency Partnership</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#0F172A] mb-1.5"><i class="fa-solid fa-message mr-1 text-gray-400"></i>Message *</label>
                            <textarea name="message" rows="4" required placeholder="Tell us about your websites and automation goals..." class="w-full px-4 py-3 text-sm rounded-xl border border-[#CBD5E1] bg-[#F8FAFC] text-[#0F172A] focus:bg-white focus:ring-2 focus:ring-green-500/20 focus:border-[#22C55E] transition-all outline-none resize-none"></textarea>
                        </div>
                        <button type="submit" class="w-full py-3.5 px-6 rounded-xl bg-[#22C55E] hover:bg-[#16A34A] text-white font-bold text-sm shadow-xl shadow-green-500/25 transition-all hover:scale-[1.01] flex items-center justify-center gap-2">
                            <i class="fa-solid fa-paper-plane"></i>Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Bottom Cards -->
<section class="py-16 bg-[#F8FAFC] border-t border-[#E2E8F0]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div data-reveal-stagger class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="card-hover p-6 rounded-3xl bg-white border border-[#E2E8F0] shadow-sm text-center space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-[#DCFCE7] flex items-center justify-center mx-auto"><i class="fa-solid fa-rocket text-[#15803D] text-xl"></i></div>
                <h3 class="text-sm font-bold text-[#0F172A]">Start a Free Trial</h3>
                <p class="text-xs text-[#64748B]">No sales call needed. Create an account and connect your first website in minutes.</p>
                <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 text-xs text-[#15803D] hover:text-[#22C55E] font-bold">Get started free <i class="fa-solid fa-arrow-right"></i></a>
            </div>
            <div class="card-hover p-6 rounded-3xl bg-white border border-[#E2E8F0] shadow-sm text-center space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-[#DCFCE7] flex items-center justify-center mx-auto"><i class="fa-solid fa-tag text-[#15803D] text-xl"></i></div>
                <h3 class="text-sm font-bold text-[#0F172A]">View Pricing</h3>
                <p class="text-xs text-[#64748B]">Compare plans for freelancers, agencies, and enterprises. Transparent monthly pricing.</p>
                <a href="{{ route('pricing') }}" class="inline-flex items-center gap-1.5 text-xs text-[#15803D] hover:text-[#22C55E] font-bold">See plans <i class="fa-solid fa-arrow-right"></i></a>
            </div>
            <div class="card-hover p-6 rounded-3xl bg-white border border-[#E2E8F0] shadow-sm text-center space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-[#DCFCE7] flex items-center justify-center mx-auto"><i class="fa-solid fa-gears text-[#15803D] text-xl"></i></div>
                <h3 class="text-sm font-bold text-[#0F172A]">Learn How It Works</h3>
                <p class="text-xs text-[#64748B]">Understand the technical pipeline behind Autoflow's AI content automation engine.</p>
                <a href="{{ route('how-it-works') }}" class="inline-flex items-center gap-1.5 text-xs text-[#15803D] hover:text-[#22C55E] font-bold">See the workflow <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
</section>

</x-marketing-layout>
