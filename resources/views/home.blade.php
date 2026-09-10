@extends('layouts.app')

@section('title', config('app.name') . ' — শিক্ষার সব খবর এক নজরে')
@section('meta_description', 'Education Times — বাংলাদেশের শিক্ষা সংবাদের বিশ্বস্ত পোর্টাল। শিক্ষা নীতিমালা, পরীক্ষা, ভর্তি, ক্যারিয়ার ও শিক্ষা বিষয়ক সর্বশেষ খবর।')

@section('content')

{{-- ============================================================ --}}
{{-- BREAKING NEWS TICKER --}}
{{-- ============================================================ --}}
@if($breakingStories->isNotEmpty())
<div class="bg-gray-900 border-b border-black">
    <div class="max-w-[1240px] mx-auto px-4 flex items-stretch h-11 relative">
        <div class="flex items-center gap-2 pr-6 z-10 bg-gray-900 shadow-[10px_0_15px_-5px_rgba(17,24,39,1)]">
            <div class="w-2 h-2 rounded-full bg-[#E02020] animate-pulse"></div>
            <span class="text-[10px] md:text-xs font-bold uppercase tracking-widest text-white">{{ $breakingLabel ?? 'ব্রেকিং নিউজ' }}</span>
        </div>
        <div class="overflow-hidden flex-1 relative flex items-center">
            {{-- Duplicating the items and scrolling -50% makes it an infinite seamless loop --}}
            <div class="ticker-track flex gap-12 items-center h-full w-max whitespace-nowrap pl-4">
                @foreach($breakingStories as $story)
                <a href="{{ route('article.show', $story->slug) }}" class="group flex items-center text-gray-300 hover:text-white text-sm md:text-base font-medium transition-colors">
                    <span class="mr-3 text-[#E02020] opacity-50 text-[10px]">●</span>
                    <span class="group-hover:underline decoration-1 underline-offset-4">{{ $story->title_bn }}</span>
                </a>
                @endforeach
                {{-- Duplicate --}}
                @foreach($breakingStories as $story)
                <a href="{{ route('article.show', $story->slug) }}" class="group flex items-center text-gray-300 hover:text-white text-sm md:text-base font-medium transition-colors" aria-hidden="true">
                    <span class="mr-3 text-[#E02020] opacity-50 text-[10px]">●</span>
                    <span class="group-hover:underline decoration-1 underline-offset-4">{{ $story->title_bn }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif

{{-- ============================================================ --}}
{{-- PREMIUM SLIDER --}}
{{-- ============================================================ --}}
<x-news.slider :articles="$sliderArticles" />

<div class="max-w-[1240px] mx-auto px-4 py-5">

    {{-- ============================================================ --}}
    {{-- HERO GRID: Lead + Secondary + Sidebar list --}}
    {{-- ============================================================ --}}
    <x-news.hero-grid
        :lead="$leadStory"
        :secondary="$featuredStories->take(4)"
        :list="$latest->take(5)" />

    {{-- ============================================================ --}}
    {{-- AD BANNER --}}
    {{-- ============================================================ --}}
    <x-ads.banner :width="970" :height="90" position="header" label="হেডার ব্যানার" />

    {{-- ============================================================ --}}
    {{-- 8-COL CONTENT + 4-COL SIDEBAR --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-1 md:grid-cols-12 gap-8">

        <div class="md:col-span-8 space-y-10">

            @if($latest->isNotEmpty())
            <section>
                <header class="flex items-center justify-between mb-5">
                    <div class="flex items-center gap-3">
                        <div class="w-1 h-6 bg-[#E02020] rounded-full shrink-0"></div>
                        <h2 class="font-serif font-black text-2xl text-[#111]">সর্বশেষ</h2>
                    </div>
                    <a href="{{ route('news.index') }}" class="text-xs font-semibold uppercase tracking-wider text-[#666] hover:text-[#E02020] transition-colors flex items-center gap-1">সব খবর <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg></a>
                </header>
                <div class="border-b-2 border-[#111] mb-5"></div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-x-5 gap-y-7">
                    @foreach($latest->take(6) as $story)
                        <x-news.article-card :article="$story" size="md" />
                    @endforeach
                </div>
            </section>
            @endif

            {{-- CATEGORY BLOCK 1: Featured layout (large + list) --}}
            @if(isset($categories[0]) && $categories[0]->articles->isNotEmpty())
                <x-news.category-block
                    :title="$categories[0]->name_bn"
                    :categorySlug="$categories[0]->slug"
                    :items="$categories[0]->articles"
                    layout="featured" />
            @endif

            {{-- VIDEO SECTION --}}
            @if($videos->isNotEmpty())
            <section>
                <header class="flex items-center justify-between mb-5">
                    <div class="flex items-center gap-3">
                        <div class="w-1 h-6 bg-[#E02020] rounded-full shrink-0"></div>
                        <h2 class="font-serif font-black text-2xl text-[#111]">ভিডিও</h2>
                    </div>
                    <a href="{{ route('videos.index') }}" class="text-xs font-semibold uppercase tracking-wider text-[#666] hover:text-[#E02020] transition-colors flex items-center gap-1">আরো ভিডিও <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg></a>
                </header>
                <div class="border-b-2 border-[#111] mb-5"></div>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-x-5 gap-y-7">
                    @foreach($videos->take(3) as $video)
                        <x-news.video-card :article="$video" />
                    @endforeach
                </div>
            </section>
            @endif

            {{-- CATEGORY BLOCK 2: list layout --}}
            @if(isset($categories[1]) && $categories[1]->articles->isNotEmpty())
                <x-news.category-block
                    :title="$categories[1]->name_bn"
                    :categorySlug="$categories[1]->slug"
                    :items="$categories[1]->articles->take(4)"
                    layout="list" />
            @endif

            {{-- AD BANNER (in-content) --}}
            <x-ads.banner :width="728" :height="90" position="article-top" label="ইন-কন্টেন্ট ব্যানার" />

            {{-- CATEGORY BLOCK 3: grid layout --}}
            @if(isset($categories[2]) && $categories[2]->articles->isNotEmpty())
                <x-news.category-block
                    :title="$categories[2]->name_bn"
                    :categorySlug="$categories[2]->slug"
                    :items="$categories[2]->articles->take(3)"
                    layout="grid" />
            @endif

            {{-- CATEGORY BLOCK 4: featured layout --}}
            @if(isset($categories[3]) && $categories[3]->articles->isNotEmpty())
                <x-news.category-block
                    :title="$categories[3]->name_bn"
                    :categorySlug="$categories[3]->slug"
                    :items="$categories[3]->articles"
                    layout="featured" />
            @endif

            {{-- NEWSLETTER STRIP --}}
            <x-news.newsletter-strip />

            {{-- CATEGORY BLOCK 5: list layout --}}
            @if(isset($categories[4]) && $categories[4]->articles->isNotEmpty())
                <x-news.category-block
                    :title="$categories[4]->name_bn"
                    :categorySlug="$categories[4]->slug"
                    :items="$categories[4]->articles->take(4)"
                    layout="list" />
            @endif

            {{-- CATEGORY BLOCK 6: grid layout --}}
            @if(isset($categories[5]) && $categories[5]->articles->isNotEmpty())
                <x-news.category-block
                    :title="$categories[5]->name_bn"
                    :categorySlug="$categories[5]->slug"
                    :items="$categories[5]->articles->take(3)"
                    layout="grid" />
            @endif
        </div>

        {{-- ============================================================ --}}
        {{-- SIDEBAR --}}
        {{-- ============================================================ --}}
        <aside class="md:col-span-4 space-y-8">
            {{-- MOST READ --}}
            <x-news.most-read :items="$mostRead" :limit="8" />

            {{-- Editor's Picks --}}
            @if($editorPicks->isNotEmpty())
            <section>
                <header class="border-b-2 border-[#111] pb-2 mb-4">
                    <h2 class="font-serif font-black text-lg text-[#111]">সম্পাদকের পছন্দ</h2>
                </header>
                <ul class="space-y-4">
                    @foreach($editorPicks as $story)
                    <li class="flex gap-3 {{ !$loop->last ? 'pb-4 border-b border-[#e5e5e5]' : '' }}">
                        <a href="{{ route('article.show', $story->slug) }}" class="block shrink-0 w-20 h-16 bg-[#f5f5f5] overflow-hidden">
                            @if($story->featured_image)
                                <img src="{{ $story->featured_image_url }}" alt="" loading="lazy" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-[#e5e5e5]"></div>
                            @endif
                        </a>
                        <a href="{{ route('article.show', $story->slug) }}" class="flex-1">
                            <h4 class="font-semibold text-sm text-[#111] leading-snug hover:underline decoration-1 underline-offset-2 line-clamp-3">{{ $story->title_bn }}</h4>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </section>
            @endif

            {{-- SIDEBAR AD --}}
            <x-ads.banner :width="300" :height="250" position="sidebar" label="সাইডবার বিজ্ঞাপন" />

            {{-- FACEBOOK --}}
            @if($facebookUrl)
            <section>
                <header class="border-b-2 border-[#111] pb-2 mb-3">
                    <h2 class="font-serif font-black text-lg text-[#111]">ফেসবুকে আমরা</h2>
                </header>
                <iframe src="https://www.facebook.com/plugins/page.php?href={{ urlencode($facebookUrl) }}&tabs=timeline&width=340&height=400&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&cta=false"
                        width="100%" height="400" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" loading="lazy" title="Facebook"></iframe>
            </section>
            @endif

            {{-- POPULAR TAGS --}}
            @if(isset($popularTags) && $popularTags->isNotEmpty())
            <section>
                <header class="border-b-2 border-[#111] pb-2 mb-3">
                    <h2 class="font-serif font-black text-lg text-[#111]">জনপ্রিয় বিষয়</h2>
                </header>
                <div class="flex flex-wrap gap-2">
                    @foreach($popularTags as $tag)
                    <a href="{{ route('search.index', ['q' => $tag->tag]) }}" class="text-xs px-2.5 py-1 border border-[#e5e5e5] text-[#444] hover:bg-[#111] hover:text-white hover:border-[#111] transition">{{ $tag->tag }}</a>
                    @endforeach
                </div>
            </section>
            @endif
        </aside>
    </div>
</div>

<style>
@keyframes ticker {
    0%   { transform: translateX(100vw); }
    100% { transform: translateX(-100%); }
}
.ticker-track {
    animation: ticker 60s linear infinite;
    padding-right: 2rem;
}
.ticker-track:hover { animation-play-state: paused; }
@media (max-width: 768px) {
    .ticker-track { animation-duration: 35s; }
}
.line-clamp-2 { display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
.line-clamp-3 { display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; }
</style>
@endsection
