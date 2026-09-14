@php
    $nlTitle = $title ?? \App\Models\Setting::get('newsletter_title', 'নিউজলেটারে যোগ দিন');
    $nlPitch = $pitch ?? \App\Models\Setting::get('newsletter_pitch', 'সর্বশেষ শিক্ষা সংবাদ সরাসরি আপনার ইমেইলে পান।');
@endphp

<section class="my-12 bg-[#111] text-white relative overflow-hidden">
    {{-- Decorative background elements --}}
    <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-[#E02020] rounded-full mix-blend-screen filter blur-3xl opacity-20 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-64 h-64 bg-[#E02020] rounded-full mix-blend-screen filter blur-3xl opacity-20 pointer-events-none"></div>

    <div class="p-8 md:p-12 flex flex-col md:flex-row items-center justify-between gap-12 relative z-10 max-w-[1240px] mx-auto">
        <div class="flex-1 text-center md:text-left">
            <h2 class="font-serif font-black text-3xl md:text-4xl text-white tracking-tight leading-tight">{{ $nlTitle }}</h2>
            <p class="text-sm md:text-base text-[#ccc] mt-3 leading-relaxed max-w-xl mx-auto md:mx-0">{{ $nlPitch }}</p>
        </div>
        <div class="w-full md:w-96 shrink-0">
            <form action="{{ route('newsletter.subscribe') }}" method="POST" class="relative">
                @csrf
                <div class="flex flex-col sm:flex-row gap-0 shadow-xl">
                    <input type="email" name="email" required placeholder="আপনার ইমেইল ঠিকানা"
                           class="flex-1 border-0 focus:outline-none focus:ring-2 focus:ring-[#E02020] px-5 py-4 text-sm bg-white text-[#111] font-medium rounded-t sm:rounded-l sm:rounded-tr-none transition-shadow">
                    <button type="submit" class="bg-[#E02020] hover:bg-[#c01818] text-white text-sm font-bold uppercase tracking-wider px-8 py-4 transition-colors rounded-b sm:rounded-r sm:rounded-bl-none flex items-center justify-center gap-2 group">
                        সাবস্ক্রাইব
                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
                @if(session('newsletter_success'))
                    <p class="newsletter-msg text-white/90 text-xs mt-3 bg-green-600/90 rounded px-3 py-2">{{ session('newsletter_success') }}</p>
                @endif
                @error('email')
                    <p class="newsletter-msg text-white/90 text-xs mt-3 bg-[#E02020] rounded px-3 py-2">{{ $message }}</p>
                @enderror
            </form>
        </div>
    </div>
</section>
