@php
    use Carbon\Carbon;
    $todayBn = Carbon::now()->locale('bn')->translatedFormat('l, j F, Y');
@endphp
<header class="bg-white border-b border-[#e5e5e5]">
    {{-- Top Utility Bar --}}
    <div class="bg-[#111] text-white text-xs">
        <div class="max-w-[1240px] mx-auto px-4 h-9 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <span class="inline tracking-wide">{{ $todayBn }}</span>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('contact.index') }}" class="hidden sm:inline hover:text-white/70 transition">যোগাযোগ</a>
                <span class="hidden sm:inline text-white/30">|</span>
                <a href="{{ route('pages.privacy') }}" class="hidden md:inline hover:text-white/70 transition">প্রাইভেসি</a>
                <span class="hidden md:inline text-white/30">|</span>
                <a href="{{ route('pages.terms') }}" class="hidden md:inline hover:text-white/70 transition">শর্তাবলী</a>
                <span class="hidden md:inline text-white/30">|</span>
                <div class="flex items-center gap-2">
                    @if($socialFacebook = \App\Models\Setting::get('social_facebook'))
                    <a href="{{ $socialFacebook }}" target="_blank" rel="noopener" class="hover:text-white/70" aria-label="Facebook">
                        <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    @endif
                    @if($socialTwitter = \App\Models\Setting::get('social_twitter'))
                    <a href="{{ $socialTwitter }}" target="_blank" rel="noopener" class="hover:text-white/70" aria-label="Twitter">
                        <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    @endif
                    @if($socialYoutube = \App\Models\Setting::get('social_youtube'))
                    <a href="{{ $socialYoutube }}" target="_blank" rel="noopener" class="hover:text-white/70" aria-label="YouTube">
                        <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Masthead --}}
    <div id="masthead" class="bg-white border-b border-[#e5e5e5] transition-all duration-200">
        <div class="max-w-[1240px] mx-auto px-4 py-4 md:py-6 flex items-center justify-between gap-4">
            {{-- Mobile menu toggle --}}
            <button type="button" id="mobileMenuToggle" class="md:hidden text-[#111]" aria-label="Open menu">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>

            <a href="/" class="flex-1 md:flex-none text-center md:text-left">
                @if($siteLogo)
                    <img src="{{ Storage::url($siteLogo) }}" alt="{{ $siteNameBn }}" class="h-14 md:h-16 w-auto mx-auto md:mx-0">
                @else
                    <div class="font-serif font-black text-[#111] leading-none">
                        <span class="block text-3xl md:text-5xl tracking-tight">Education Times</span>
                        <span class="block text-[10px] md:text-xs font-sans font-normal tracking-[0.3em] text-[#666] mt-1.5 uppercase">এডুকেশন টাইমস · বাংলাদেশ</span>
                    </div>
                @endif
            </a>

            <div class="flex items-center gap-3">
                {{-- Search --}}
                <button type="button" id="searchToggle" class="text-[#111] hover:text-[#666] p-1.5" aria-label="Search">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>

                {{-- Auth --}}
                @auth
                    <div class="hidden md:flex items-center gap-3 text-sm">
                        @if(Auth::user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="bg-[#111] text-white px-3 py-1.5 text-xs font-semibold uppercase tracking-wider hover:bg-black transition">অ্যাডমিন</a>
                        @else
                        <a href="{{ route('dashboard') }}" class="bg-[#111] text-white px-3 py-1.5 text-xs font-semibold uppercase tracking-wider hover:bg-black transition">ড্যাশবোর্ড</a>
                        @endif
                        <a href="{{ route('profile.show') }}" class="text-[#111] hover:text-[#666]">{{ Auth::user()->name }}</a>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="hidden sm:inline-block bg-[#111] text-white px-4 py-1.5 text-xs font-semibold uppercase tracking-wider hover:bg-black transition">লগইন</a>
                @endauth
            </div>
        </div>
    </div>

    {{-- Search Drawer --}}
    <div id="searchDrawer" class="hidden bg-white border-b border-[#e5e5e5]">
        <form action="{{ route('search.index') }}" method="GET" class="max-w-[1240px] mx-auto px-4 py-4 flex items-center gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="খবর খুঁজুন..." autofocus
                   class="flex-1 border border-[#111] px-4 py-2.5 text-sm focus:outline-none focus:border-[#111] focus:ring-1 focus:ring-[#111]">
            <button type="submit" class="bg-[#111] text-white px-6 py-2.5 text-sm font-semibold uppercase tracking-wider hover:bg-black transition">খুঁজুন</button>
            <button type="button" id="searchClose" class="text-[#666] hover:text-[#111] p-2" aria-label="Close search">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </form>
    </div>

    {{-- Mobile Menu Drawer (Slide In) --}}
    <div id="mobileMenuOverlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 opacity-0 pointer-events-none transition-opacity duration-300"></div>
    <div id="mobileMenuDrawer" class="fixed inset-y-0 left-0 w-4/5 max-w-sm bg-white z-50 transform -translate-x-full transition-transform duration-300 flex flex-col shadow-2xl">
        <div class="flex items-center justify-between px-4 h-14 border-b border-[#e5e5e5] shrink-0">
            <span class="font-serif font-black text-lg">Education Times</span>
            <button type="button" id="mobileMenuClose" class="p-2 -mr-2 text-[#666] hover:text-[#111]" aria-label="Close menu">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <nav class="overflow-y-auto flex-1 overscroll-contain">
            <a href="/" class="block px-5 py-3.5 text-sm font-semibold uppercase tracking-wider border-b border-[#e5e5e5] {{ request()->routeIs('home') ? 'text-white bg-[#111]' : 'text-[#111]' }}">প্রথম পাতা</a>
            @if(isset($navCategories) && $navCategories->isNotEmpty())
                @foreach($navCategories as $cat)
                <a href="{{ route('article.category', $cat->slug) }}" class="block px-5 py-3.5 text-sm font-semibold border-b border-[#e5e5e5] text-[#111]">{{ $cat->name_bn }}</a>
                @endforeach
            @endif
            <a href="{{ route('archive.index') }}" class="block px-5 py-3.5 text-sm font-semibold border-b border-[#e5e5e5] text-[#111]">আর্কাইভ</a>
            <a href="{{ route('contact.index') }}" class="block px-5 py-3.5 text-sm font-semibold border-b border-[#e5e5e5] text-[#111]">যোগাযোগ</a>
            @guest
            <a href="{{ route('login') }}" class="block px-5 py-3.5 text-sm font-semibold border-b border-[#e5e5e5] text-[#111]">লগইন</a>
            <a href="{{ route('register') }}" class="block px-5 py-3.5 text-sm font-semibold text-[#111]">রেজিস্টার</a>
            @else
                <a href="{{ route('profile.show') }}" class="block px-5 py-3.5 text-sm font-semibold border-b border-[#e5e5e5] text-[#111]">{{ Auth::user()->name }}</a>
                @if(Auth::user()->is_admin)
                <a href="{{ route('admin.dashboard') }}" class="block px-5 py-3.5 text-sm font-semibold border-b border-[#e5e5e5] text-[#111]">অ্যাডমিন প্যানেল</a>
                @else
                <a href="{{ route('dashboard') }}" class="block px-5 py-3.5 text-sm font-semibold border-b border-[#e5e5e5] text-[#111]">ড্যাশবোর্ড</a>
                @endif
            @endguest
        </nav>
    </div>
</header>

{{-- Sticky Category Nav (lives outside header so it sticks independently) --}}
@if(isset($navCategories) && $navCategories->isNotEmpty())
<nav id="stickyNav" class="bg-white border-b-2 border-[#111] z-40 hidden md:block sticky top-0 transition-shadow duration-200">
    <div class="max-w-[1240px] mx-auto px-4 overflow-x-auto nav-scroll">
        <ul class="flex items-stretch -mx-3 whitespace-nowrap min-w-max">
            <li class="flex items-center">
                <button type="button" id="desktopMenuToggle" class="block px-3 py-3.5 text-sm font-bold uppercase tracking-wider text-white bg-[#E02020] hover:bg-[#c01818] transition flex items-center gap-1.5">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    সব বিভাগ
                </button>
            </li>
            <li>
                <a href="/" class="block px-3 py-3.5 text-sm font-bold uppercase tracking-wider {{ request()->routeIs('home') ? 'text-white bg-[#111]' : 'text-[#111] hover:text-white hover:bg-[#111]' }} transition">
                    প্রথম পাতা
                </a>
            </li>
            @foreach($navCategories->take(8) as $cat)
            <li>
                <a href="{{ route('article.category', $cat->slug) }}"
                   class="block px-3 py-3.5 text-sm font-semibold {{ request()->routeIs('article.category') && request()->route('slug') === $cat->slug ? 'text-white bg-[#111]' : 'text-[#111] hover:text-white hover:bg-[#111]' }} transition">
                    {{ $cat->name_bn }}
                </a>
            </li>
            @endforeach

            <li class="flex items-center">
                <a href="{{ route('archive.index') }}" class="block px-3 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#666] hover:text-[#111]">আর্কাইভ</a>
            </li>
        </ul>
    </div>
</nav>
@endif

<script>
(function() {
    // Search drawer
    var sToggle = document.getElementById('searchToggle');
    var sClose = document.getElementById('searchClose');
    var sDrawer = document.getElementById('searchDrawer');
    if (sToggle && sDrawer) {
        sToggle.addEventListener('click', function() {
            sDrawer.classList.toggle('hidden');
            if (!sDrawer.classList.contains('hidden')) {
                var input = sDrawer.querySelector('input');
                if (input) setTimeout(function(){ input.focus(); }, 50);
            }
        });
    }
    if (sClose && sDrawer) {
        sClose.addEventListener('click', function() {
            sDrawer.classList.add('hidden');
        });
    }

    // Mobile menu drawer with animation
    var openBtn = document.getElementById('mobileMenuToggle');
    var desktopBtn = document.getElementById('desktopMenuToggle');
    var closeBtn = document.getElementById('mobileMenuClose');
    var overlay = document.getElementById('mobileMenuOverlay');
    var drawer = document.getElementById('mobileMenuDrawer');

    function openDrawer() {
        overlay.classList.remove('opacity-0', 'pointer-events-none');
        overlay.classList.add('opacity-100');
        drawer.classList.remove('-translate-x-full');
        document.body.style.overflow = 'hidden';
    }

    function closeDrawer() {
        overlay.classList.add('opacity-0', 'pointer-events-none');
        overlay.classList.remove('opacity-100');
        drawer.classList.add('-translate-x-full');
        document.body.style.overflow = '';
    }

    if (openBtn && overlay && drawer) {
        openBtn.addEventListener('click', openDrawer);
    }
    if (desktopBtn && overlay && drawer) {
        desktopBtn.addEventListener('click', openDrawer);
    }
    if (closeBtn) {
        closeBtn.addEventListener('click', closeDrawer);
    }
    if (overlay) {
        overlay.addEventListener('click', closeDrawer);
    }

    // Drag-to-scroll for category nav
    document.querySelectorAll('.nav-scroll').forEach(function(el) {
        var isDown = false, startX = 0, startScroll = 0;
        el.addEventListener('mousedown', function(e) {
            if (e.target.closest('a')) return;
            isDown = true;
            startX = e.pageX - el.offsetLeft;
            startScroll = el.scrollLeft;
        });
        el.addEventListener('mouseleave', function() { isDown = false; });
        el.addEventListener('mouseup', function() { isDown = false; });
        el.addEventListener('mousemove', function(e) {
            if (!isDown) return;
            e.preventDefault();
            var x = e.pageX - el.offsetLeft;
            el.scrollLeft = startScroll - (x - startX) * 1.2;
        });
    });

    // Sticky nav shadow on scroll
    var stickyNav = document.getElementById('stickyNav');
    var masthead = document.getElementById('masthead');
    var utilityBar = masthead ? masthead.previousElementSibling : null;
    var lastScrollY = 0;

    if (stickyNav || masthead) {
        window.addEventListener('scroll', function() {
            var y = window.scrollY;

            // Add shadow to sticky nav when page has scrolled
            if (stickyNav) {
                if (y > 10) {
                    stickyNav.classList.add('shadow-md');
                } else {
                    stickyNav.classList.remove('shadow-md');
                }
            }

            // Compact the masthead on scroll down
            if (masthead) {
                if (y > 60) {
                    masthead.style.paddingTop = '8px';
                    masthead.style.paddingBottom = '8px';
                } else {
                    masthead.style.paddingTop = '';
                    masthead.style.paddingBottom = '';
                }
            }

            lastScrollY = y;
        }, { passive: true });
    }
})();
</script>

<style>
.nav-scroll {
    -ms-overflow-style: none;
    scrollbar-width: none;
    -webkit-overflow-scrolling: touch;
    overscroll-behavior-x: contain;
    cursor: grab;
}
.nav-scroll:active { cursor: grabbing; }
.nav-scroll::-webkit-scrollbar { display: none; }
</style>
