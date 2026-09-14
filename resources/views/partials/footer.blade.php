@php
    $siteName = \App\Models\Setting::get('site_name', 'Education Times');
    $tagline = \App\Models\Setting::get('site_tagline', '');
    $aboutText = \App\Models\Setting::get('about_text', '');
    $contactEmail = \App\Models\Setting::get('contact_email', \App\Models\Setting::get('footer_email', ''));
    $contactPhone = \App\Models\Setting::get('contact_phone', '');
    $contactAddress = \App\Models\Setting::get('contact_address', '');
    $editorName = \App\Models\Setting::get('editor_name', '');
    $editorDesig = \App\Models\Setting::get('editor_designation', 'সম্পাদক');
    $publisherName = \App\Models\Setting::get('publisher_name', '');
    $footerTagline = \App\Models\Setting::get('footer_tagline', '');
@endphp

<footer class="bg-[#111] text-white border-t-4 border-[#E02020] mt-12">
    {{-- Popular Tags Strip --}}
    @if(isset($popularTags) && $popularTags->isNotEmpty())
    <div class="border-b border-[#222] bg-[#0a0a0a]">
        <div class="max-w-[1240px] mx-auto px-4 py-5 flex items-center flex-wrap gap-3">
            <span class="text-[11px] font-bold uppercase tracking-widest text-white mr-2 flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-[#E02020] animate-pulse"></span>
                জনপ্রিয় বিষয়
            </span>
            @foreach($popularTags as $tag)
            <a href="{{ route('search.index', ['q' => $tag->tag]) }}" class="text-xs px-3 py-1.5 bg-[#1a1a1a] border border-[#333] text-[#ccc] hover:bg-[#E02020] hover:text-white hover:border-[#E02020] rounded transition-all shadow-sm">{{ $tag->tag }}</a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Main Footer --}}
    <div class="max-w-[1240px] mx-auto px-4 py-16">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-12 lg:gap-8">
            {{-- Brand + About --}}
            <div class="md:col-span-4 pr-0 lg:pr-8">
                <a href="/" class="inline-block mb-6">
                    @if(isset($siteFooterLogo) && $siteFooterLogo)
                        <img src="{{ Storage::url($siteFooterLogo) }}" alt="{{ $siteName }}" class="h-12 w-auto object-contain">
                    @else
                        <span class="font-serif font-black text-3xl text-white tracking-tight leading-none">Education Times</span>
                        <span class="block text-[10px] font-bold tracking-[0.3em] text-[#E02020] mt-2 uppercase">এডুকেশন টাইমস · বাংলাদেশ</span>
                    @endif
                </a>
                <p class="text-sm text-[#999] leading-relaxed">
                    {{ $aboutText ?: 'বাংলাদেশের শিক্ষা সংবাদের বিশ্বস্ত পোর্টাল। শিক্ষা নীতিমালা, পরীক্ষা, ভর্তি, ক্যারিয়ার ও শিক্ষা বিষয়ক সর্বশেষ খবর সবার আগে।' }}
                </p>
                @if($contactAddress)
                <p class="text-sm text-[#999] mt-6 leading-relaxed">
                    <strong class="text-white block mb-1.5 text-xs uppercase tracking-wider">সম্পাদকীয় কার্যালয়</strong>
                    {{ $contactAddress }}
                </p>
                @endif
                <div class="flex items-center gap-2.5 mt-8">
                    @if($fb = \App\Models\Setting::get('social_facebook'))
                    <a href="{{ $fb }}" target="_blank" rel="noopener" class="w-10 h-10 rounded-full bg-[#1a1a1a] text-white hover:bg-[#1877F2] hover:scale-110 flex items-center justify-center transition-all shadow-sm" title="Facebook">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    @endif
                    @if($tw = \App\Models\Setting::get('social_twitter'))
                    <a href="{{ $tw }}" target="_blank" rel="noopener" class="w-10 h-10 rounded-full bg-[#1a1a1a] text-white hover:bg-black hover:scale-110 flex items-center justify-center transition-all shadow-sm" title="X (Twitter)">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    @endif
                    @if($yt = \App\Models\Setting::get('social_youtube'))
                    <a href="{{ $yt }}" target="_blank" rel="noopener" class="w-10 h-10 rounded-full bg-[#1a1a1a] text-white hover:bg-[#FF0000] hover:scale-110 flex items-center justify-center transition-all shadow-sm" title="YouTube">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                    @endif
                </div>
            </div>

            {{-- Sections --}}
            <div class="md:col-span-2">
                <h4 class="text-[11px] font-bold uppercase tracking-widest text-white mb-6 flex items-center gap-2">
                    <span class="w-1 h-3.5 bg-[#E02020] rounded-full"></span>
                    বিভাগ
                </h4>
                <ul class="space-y-3.5 text-sm">
                    @if(isset($footerCategories) && $footerCategories->isNotEmpty())
                        @foreach($footerCategories->take(7) as $cat)
                        <li><a href="{{ route('article.category', $cat->slug) }}" class="text-[#aaa] hover:text-white transition-colors flex items-center gap-2 group"><span class="w-1 h-1 rounded-full bg-[#333] group-hover:bg-[#E02020] transition-colors"></span>{{ $cat->name_bn }}</a></li>
                        @endforeach
                    @endif
                </ul>
            </div>

            <div class="md:col-span-2">
                <h4 class="text-[11px] font-bold uppercase tracking-widest text-white mb-6 flex items-center gap-2">
                    <span class="w-1 h-3.5 bg-[#E02020] rounded-full"></span>
                    সেবা
                </h4>
                <ul class="space-y-3.5 text-sm">
                    <li><a href="{{ route('home') }}" class="text-[#aaa] hover:text-white transition-colors flex items-center gap-2 group"><span class="w-1 h-1 rounded-full bg-[#333] group-hover:bg-[#E02020] transition-colors"></span>প্রথম পাতা</a></li>
                    <li><a href="{{ route('archive.index') }}" class="text-[#aaa] hover:text-white transition-colors flex items-center gap-2 group"><span class="w-1 h-1 rounded-full bg-[#333] group-hover:bg-[#E02020] transition-colors"></span>আর্কাইভ</a></li>
                    <li><a href="{{ route('contact.index') }}" class="text-[#aaa] hover:text-white transition-colors flex items-center gap-2 group"><span class="w-1 h-1 rounded-full bg-[#333] group-hover:bg-[#E02020] transition-colors"></span>যোগাযোগ</a></li>
                    @if(\Illuminate\Support\Facades\Route::has('newsletter.index'))
                    <li><a href="{{ route('newsletter.index') }}" class="text-[#aaa] hover:text-white transition-colors flex items-center gap-2 group"><span class="w-1 h-1 rounded-full bg-[#333] group-hover:bg-[#E02020] transition-colors"></span>নিউজলেটার</a></li>
                    @endif
                    <li><a href="{{ route('pages.privacy') }}" class="text-[#aaa] hover:text-white transition-colors flex items-center gap-2 group"><span class="w-1 h-1 rounded-full bg-[#333] group-hover:bg-[#E02020] transition-colors"></span>প্রাইভেসি পলিসি</a></li>
                    <li><a href="{{ route('pages.terms') }}" class="text-[#aaa] hover:text-white transition-colors flex items-center gap-2 group"><span class="w-1 h-1 rounded-full bg-[#333] group-hover:bg-[#E02020] transition-colors"></span>শর্তাবলী</a></li>
                </ul>
            </div>

            {{-- Contact + Editor --}}
            <div class="md:col-span-4 lg:pl-4">
                <h4 class="text-[11px] font-bold uppercase tracking-widest text-white mb-6 flex items-center gap-2">
                    <span class="w-1 h-3.5 bg-[#E02020] rounded-full"></span>
                    যোগাযোগ
                </h4>
                <ul class="space-y-4 text-sm text-[#aaa]">
                    @if($contactEmail)
                    <li class="flex items-start gap-3 group">
                        <div class="w-8 h-8 rounded-full bg-[#1a1a1a] flex items-center justify-center shrink-0 group-hover:bg-[#E02020] group-hover:text-white transition-colors">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <a href="mailto:{{ $contactEmail }}" class="mt-1.5 hover:text-white transition-colors">{{ $contactEmail }}</a>
                    </li>
                    @endif
                    @if($contactPhone)
                    <li class="flex items-start gap-3 group">
                        <div class="w-8 h-8 rounded-full bg-[#1a1a1a] flex items-center justify-center shrink-0 group-hover:bg-[#E02020] group-hover:text-white transition-colors">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.95.68l1.5 4.49a1 1 0 01-.5 1.21l-2.26 1.13a11 11 0 005.52 5.52l1.13-2.26a1 1 0 011.21-.5l4.49 1.5a1 1 0 01.68.95V19a2 2 0 01-2 2h-1C9.72 21 3 14.28 3 6V5z"/></svg>
                        </div>
                        <span class="mt-1.5">{{ $contactPhone }}</span>
                    </li>
                    @endif
                </ul>

                <h4 class="text-[11px] font-bold uppercase tracking-widest text-white mt-10 mb-5 flex items-center gap-2">
                    <span class="w-1 h-3.5 bg-[#E02020] rounded-full"></span>
                    সম্পাদকীয়
                </h4>
                <div class="p-5 bg-[#1a1a1a] border border-[#222] rounded-sm">
                    @if($editorName)
                    <p class="text-sm text-[#ccc] leading-relaxed">
                        <span class="text-[#666] text-xs uppercase tracking-widest block mb-1">সম্পাদক</span>
                        <strong class="text-white">{{ $editorName }}</strong>
                        @if($editorDesig)<span class="text-[#888] text-xs ml-1">({{ $editorDesig }})</span>@endif
                    </p>
                    @endif
                    @if($publisherName)
                    <div class="w-8 h-px bg-[#333] my-3"></div>
                    <p class="text-sm text-[#ccc] leading-relaxed">
                        <span class="text-[#666] text-xs uppercase tracking-widest block mb-1">প্রকাশক</span>
                        <strong class="text-white">{{ $publisherName }}</strong>
                    </p>
                    @endif
                </div>

                <h4 class="text-[11px] font-bold uppercase tracking-widest text-white mt-10 mb-5 flex items-center gap-2">
                    <span class="w-1 h-3.5 bg-[#E02020] rounded-full"></span>
                    Partners
                </h4>
                <div class="flex flex-wrap gap-3">
                    @php
                        $partners = \App\Models\Partner::where('is_active', true)->orderBy('order')->get();
                    @endphp
                    @if($partners->isNotEmpty())
                        @foreach($partners as $partner)
                        <a href="{{ $partner->url ?: '#' }}" target="_blank" rel="noopener" class="block w-20 h-20 bg-white rounded border border-[#222] hover:border-[#E02020] transition-all p-1.5 flex items-center justify-center" title="{{ $partner->name }}">
                            <img src="{{ Storage::url($partner->logo) }}" alt="{{ $partner->name }}" class="max-w-full max-h-full object-contain">
                        </a>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Bar --}}
    <div class="border-t border-[#222] bg-[#050505]">
        <div class="max-w-[1240px] mx-auto px-4 py-6 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-[#666]">
            <p>{{ \App\Models\Setting::get('footer_copyright', '© ' . date('Y') . ' Education Times. সর্বস্বত্ব সংরক্ষিত।') }}</p>
            <p class="flex items-center gap-1">Crafted by <a href="https://prodo.top" target="_blank" rel="noopener" class="text-white hover:text-[#E02020] font-bold tracking-wider transition-colors">ProDo</a></p>
        </div>
    </div>
</footer>
