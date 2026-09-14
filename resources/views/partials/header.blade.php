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
                <span class="hidden sm:inline text-white/30">|</span>
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
                     <img src="{{ Storage::url($siteLogo) }}" alt="{{ $siteNameBn }}" class="h-16 md:h-20 lg:h-24 w-auto mx-auto md:mx-0">
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

                {{-- Social --}}
                <div class="hidden md:flex items-center gap-2.5">
                    @if($socialFacebook = \App\Models\Setting::get('social_facebook'))
                    <a href="{{ $socialFacebook }}" target="_blank" rel="noopener" class="text-[#111] hover:text-[#E02020] transition" aria-label="Facebook">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    @endif
                    @if($socialTwitter = \App\Models\Setting::get('social_twitter'))
                    <a href="{{ $socialTwitter }}" target="_blank" rel="noopener" class="text-[#111] hover:text-[#E02020] transition" aria-label="Twitter">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    @endif
                    @if($socialYoutube = \App\Models\Setting::get('social_youtube'))
                    <a href="{{ $socialYoutube }}" target="_blank" rel="noopener" class="text-[#111] hover:text-[#E02020] transition" aria-label="YouTube">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                    @endif
                    @if($socialInstagram = \App\Models\Setting::get('social_instagram'))
                    <a href="{{ $socialInstagram }}" target="_blank" rel="noopener" class="text-[#111] hover:text-[#E02020] transition" aria-label="Instagram">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                    </a>
                    @endif
                    @if($socialLinkedin = \App\Models\Setting::get('social_linkedin'))
                    <a href="{{ $socialLinkedin }}" target="_blank" rel="noopener" class="text-[#111] hover:text-[#E02020] transition" aria-label="LinkedIn">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    </a>
                    @endif
                    @if($socialWhatsapp = \App\Models\Setting::get('social_whatsapp'))
                    <a href="{{ $socialWhatsapp }}" target="_blank" rel="noopener" class="text-[#111] hover:text-[#E02020] transition" aria-label="WhatsApp">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448L.057 24zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.218.651 3.726 1.964 5.389l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.479-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    </a>
                    @endif
                </div>

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
@php
    $mdNavCats = $navCategories->take(3);
    $lgOnlyCats = $navCategories->slice(3, 2);
    $dropdownCats = $navCategories->slice(3);
@endphp
<nav id="stickyNav" class="bg-white border-b-2 border-[#111] z-40 sticky top-0 transition-shadow duration-200">
    <div class="max-w-[1240px] mx-auto px-4">
        {{-- Desktop: fixed layout with সব বিভাগ mega dropdown --}}
        <ul class="hidden md:flex items-stretch -mx-3">
            <li class="group relative flex items-center" id="allSectionsWrap">
                <button type="button" id="desktopMenuToggle" class="flex items-center gap-1.5 px-3 py-3.5 text-sm font-bold uppercase tracking-wider text-white bg-[#E02020] hover:bg-[#c01818] transition" aria-haspopup="true" aria-expanded="false" aria-controls="allSectionsPanel">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    <span class="xl:inline hidden">সব বিভাগ</span>
                    <span class="xl:hidden">বিভাগ</span>
                    <svg class="h-3 w-3 transition-transform duration-200 group-hover:rotate-180 group-[.is-open]:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                @if($dropdownCats->isNotEmpty())
                <div id="allSectionsPanel" class="absolute left-0 top-full -mt-0.5 pt-0.5 invisible opacity-0 translate-y-1 transition duration-200 group-hover:visible group-hover:opacity-100 group-hover:translate-y-0 group-focus-within:visible group-focus-within:opacity-100 group-focus-within:translate-y-0 group-[.is-open]:visible group-[.is-open]:opacity-100 group-[.is-open]:translate-y-0 z-50">
                    <div class="w-64 bg-white border border-[#e5e5e5] shadow-xl py-1">
                        <a href="{{ route('archive.index') }}" class="flex items-center justify-between px-4 py-2.5 text-sm font-semibold text-[#E02020] hover:bg-[#f5f5f5] transition">
                            সব খবর
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                        <div class="border-t border-[#e5e5e5] my-1"></div>
                        @foreach($dropdownCats as $cat)
                        <a href="{{ route('article.category', $cat->slug) }}" class="flex items-center justify-between px-4 py-2.5 text-sm font-semibold text-[#111] hover:bg-[#f5f5f5] hover:text-[#E02020] transition">
                            {{ $cat->name_bn }}
                            <svg class="h-3.5 w-3.5 text-[#bbb]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </li>
            <li>
                <a href="/" class="block px-3 py-3.5 text-sm font-bold uppercase tracking-wider {{ request()->routeIs('home') ? 'text-white bg-[#111]' : 'text-[#111] hover:text-white hover:bg-[#111]' }} transition">
                    প্রথম পাতা
                </a>
            </li>
            @foreach($navCategories as $cat)
                @php $pos = $loop->index; @endphp
                <li class="{{ $pos < 3 ? '' : ($pos < 5 ? 'hidden lg:flex items-stretch' : 'hidden') }}">
                    <a href="{{ route('article.category', $cat->slug) }}"
                       class="block px-3 py-3.5 text-sm font-semibold {{ request()->routeIs('article.category') && request()->route('slug') === $cat->slug ? 'text-white bg-[#111]' : 'text-[#111] hover:text-white hover:bg-[#111]' }} transition">
                        {{ $cat->name_bn }}
                    </a>
                </li>
                @if($pos == 4) @break @endif
            @endforeach
            @if($dropdownCats->isEmpty())
            <li class="flex items-center">
                <a href="{{ route('archive.index') }}" class="block px-3 py-3.5 text-sm font-bold uppercase tracking-wider text-[#111] hover:text-white hover:bg-[#111] transition">আরো</a>
            </li>
            @endif
        </ul>

        {{-- Mobile: horizontal drag/touch scroll --}}
        <div class="md:hidden relative">
            <div class="overflow-x-auto nav-scroll">
                <ul class="flex items-stretch -mx-3 whitespace-nowrap min-w-max">
                    <li>
                        <a href="/" class="block px-3 py-3.5 text-sm font-bold uppercase tracking-wider {{ request()->routeIs('home') ? 'text-white bg-[#111]' : 'text-[#111]' }} transition">
                            প্রথম পাতা
                        </a>
                    </li>
                    @foreach($navCategories as $cat)
                    <li>
                        <a href="{{ route('article.category', $cat->slug) }}"
                           class="block px-3 py-3.5 text-sm font-semibold {{ request()->routeIs('article.category') && request()->route('slug') === $cat->slug ? 'text-white bg-[#111]' : 'text-[#111]' }} transition">
                            {{ $cat->name_bn }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            {{-- Edge fade hinting more items off-screen --}}
            <div class="pointer-events-none absolute inset-y-0 right-0 w-10 bg-gradient-to-l from-white to-transparent"></div>
        </div>
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
    var closeBtn = document.getElementById('mobileMenuClose');
    var overlay = document.getElementById('mobileMenuOverlay');
    var drawer = document.getElementById('mobileMenuDrawer');

    function openDrawer() {
        overlay.classList.remove('opacity-0', 'pointer-events-none', 'inert');
        overlay.classList.add('opacity-100');
        overlay.setAttribute('aria-hidden', 'false');
        drawer.classList.remove('-translate-x-full', 'inert');
        drawer.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function closeDrawer() {
        overlay.classList.add('opacity-0', 'pointer-events-none', 'inert');
        overlay.classList.remove('opacity-100');
        overlay.setAttribute('aria-hidden', 'true');
        drawer.classList.add('-translate-x-full', 'inert');
        drawer.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    if (overlay) {
        overlay.setAttribute('aria-hidden', 'true');
        overlay.classList.add('inert');
    }
    if (drawer) {
        drawer.setAttribute('aria-hidden', 'true');
        drawer.classList.add('inert');
    }

    if (openBtn && overlay && drawer) {
        openBtn.addEventListener('click', openDrawer);
    }
    if (closeBtn) {
        closeBtn.addEventListener('click', closeDrawer);
    }
    if (overlay) {
        overlay.addEventListener('click', closeDrawer);
    }

    // Desktop "সব বিভাগ" dropdown (click/keyboard toggle; hover handled by CSS)
    var desktopBtn = document.getElementById('desktopMenuToggle');
    var allSectionsWrap = document.getElementById('allSectionsWrap');
    if (desktopBtn && allSectionsWrap) {
        function setDropdownOpen(open) {
            allSectionsWrap.classList.toggle('is-open', open);
            desktopBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
        }
        desktopBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            setDropdownOpen(!allSectionsWrap.classList.contains('is-open'));
        });
        document.addEventListener('click', function(e) {
            if (!allSectionsWrap.contains(e.target)) setDropdownOpen(false);
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') setDropdownOpen(false);
        });
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
