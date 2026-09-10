{{-- App-like Bottom Navigation for Mobile --}}
<div class="md:hidden fixed bottom-0 inset-x-0 bg-white border-t border-[#e5e5e5] z-40 pb-safe">
    <div class="flex justify-around items-center h-14">
        {{-- Home --}}
        <a href="/" class="flex flex-col items-center justify-center w-full h-full text-center {{ request()->routeIs('home') ? 'text-[#111]' : 'text-[#666] hover:text-[#111]' }}">
            <svg class="h-6 w-6 mb-0.5" fill="{{ request()->routeIs('home') ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('home') ? '0' : '1.5' }}">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="text-[10px] font-semibold">হোম</span>
        </a>

        {{-- All News / Timeline --}}
        <a href="{{ route('news.index') }}" class="flex flex-col items-center justify-center w-full h-full text-center {{ request()->routeIs('news.index') ? 'text-[#111]' : 'text-[#666] hover:text-[#111]' }}">
            <svg class="h-6 w-6 mb-0.5" fill="{{ request()->routeIs('news.index') ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('news.index') ? '0' : '1.5' }}">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
            </svg>
            <span class="text-[10px] font-semibold">সব খবর</span>
        </a>

        {{-- Search (Triggers header search) --}}
        <button type="button" onclick="document.getElementById('searchToggle')?.click()" class="flex flex-col items-center justify-center w-full h-full text-center text-[#666] hover:text-[#111]">
            <svg class="h-6 w-6 mb-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <span class="text-[10px] font-semibold">খুঁজুন</span>
        </button>

        {{-- Menu (Triggers drawer) --}}
        <button type="button" onclick="document.getElementById('mobileMenuToggle')?.click()" class="flex flex-col items-center justify-center w-full h-full text-center text-[#666] hover:text-[#111]">
            <svg class="h-6 w-6 mb-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <span class="text-[10px] font-semibold">মেনু</span>
        </button>
    </div>
</div>
