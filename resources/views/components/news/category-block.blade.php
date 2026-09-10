@props([
    'title' => '',
    'categorySlug' => null,
    'items' => [],
    'layout' => 'grid',  // grid | list | featured
    'seeAll' => true,
])

@php
    $items = collect($items);
@endphp

@if($items->isNotEmpty())
<section class="mb-10">
    {{-- Section Header --}}
    <header class="flex items-center justify-between mb-5">
        <div class="flex items-center gap-3">
            <div class="w-1 h-6 bg-[#E02020] rounded-full shrink-0"></div>
            <h2 class="font-serif font-black text-xl md:text-2xl text-[#111]">{{ $title }}</h2>
        </div>
        @if($seeAll && $categorySlug)
        <a href="{{ route('article.category', $categorySlug) }}" class="text-xs font-semibold uppercase tracking-wider text-[#666] hover:text-[#E02020] transition-colors flex items-center gap-1">সব দেখুন <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg></a>
        @elseif($seeAll)
        <a href="#" class="text-xs font-semibold uppercase tracking-wider text-[#666] hover:text-[#E02020] transition-colors flex items-center gap-1">সব দেখুন <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg></a>
        @endif
    </header>
    <div class="border-b-2 border-[#111] mb-5"></div>

    @if($layout === 'featured')
        {{-- 1 large lead + list items --}}
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
            <div class="md:col-span-7">
                <x-news.article-card :article="$items->first()" size="lg" :showSummary="true" />
            </div>
            <ul class="md:col-span-5 space-y-4 md:border-l md:border-[#e5e5e5] md:pl-6">
                @foreach($items->slice(1, 4) as $item)
                <li class="flex gap-3 {{ !$loop->last ? 'pb-4 border-b border-[#f0f0f0]' : '' }}">
                    <a href="{{ route('article.show', $item->slug) }}" class="block shrink-0 w-20 h-16 bg-[#f0f0f0] overflow-hidden">
                        @if($item->featured_image)
                            <img src="{{ $item->featured_image_url }}" alt="" loading="lazy" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-[#e0e0e0]"></div>
                        @endif
                    </a>
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('article.show', $item->slug) }}" class="group">
                            <h4 class="font-semibold text-sm text-[#111] leading-snug group-hover:text-[#E02020] transition-colors duration-200 line-clamp-3">{{ $item->title_bn }}</h4>
                        </a>
                        <p class="text-[10px] text-[#999] mt-1">{{ $item->published_at?->diffForHumans() }}</p>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    @elseif($layout === 'list')
        {{-- 2-col grid of horizontal cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
            @foreach($items as $item)
                <x-news.article-card :article="$item" size="md" layout="horizontal" />
            @endforeach
        </div>
    @else
        {{-- default: 3-col grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-7">
            @foreach($items as $item)
                <x-news.article-card :article="$item" size="md" />
            @endforeach
        </div>
    @endif
</section>
@endif
