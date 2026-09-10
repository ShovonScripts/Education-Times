@props([
    'article',
    'showTag' => true,
])

@php
    if (!isset($article) || !$article) return;
@endphp

<article class="group">
    <a href="{{ route('article.show', $article->slug) }}" class="block aspect-video bg-[#0d0d0d] overflow-hidden relative">
        @if($article->featured_image)
            <img src="{{ $article->featured_image_url }}" alt="{{ $article->title_bn }}" loading="lazy" class="w-full h-full object-cover group-hover:opacity-80 transition">
        @else
            <div class="w-full h-full bg-[#1a1a1a]"></div>
        @endif
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="w-14 h-14 md:w-16 md:h-16 rounded-full bg-white/90 group-hover:bg-white flex items-center justify-center transition shadow-lg">
                <svg class="h-6 w-6 md:h-7 md:w-7 text-[#111] ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
            </div>
        </div>
        <div class="absolute bottom-2 right-2 bg-black/80 text-white text-[10px] px-1.5 py-0.5 font-bold uppercase tracking-wider">ভিডিও</div>
    </a>
    @if($showTag && $article->category)
    <a href="{{ route('article.category', $article->category->slug) }}" class="text-[10px] font-bold uppercase tracking-widest text-[#111] mt-2 inline-block border-l-2 border-[#111] pl-1.5">{{ $article->category->name_bn }}</a>
    @endif
    <a href="{{ route('article.show', $article->slug) }}" class="block">
        <h3 class="font-bold text-sm md:text-base text-[#111] leading-snug mt-1.5 group-hover:underline decoration-1 underline-offset-2 line-clamp-2">{{ $article->title_bn }}</h3>
    </a>
    <p class="text-[10px] text-[#888] mt-1">
        <time datetime="{{ $article->published_at?->toIso8601String() }}">{{ $article->published_at?->diffForHumans() }}</time>
    </p>
</article>
