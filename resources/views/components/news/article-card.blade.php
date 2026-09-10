@props([
    'article',
    'size' => 'md',          // lg | md | sm
    'showTag' => true,
    'showSummary' => false,
    'showAuthor' => true,
    'showTime' => true,
    'layout' => 'vertical',   // vertical | horizontal
    'showImage' => true,
])

@php
    use Carbon\Carbon;
    if (!isset($article) || !$article) return;

    $imageClass = match($size) {
        'lg' => 'aspect-[16/9]',
        'sm' => 'aspect-[16/10]',
        default => 'aspect-[16/9]',
    };

    $titleClass = match($size) {
        'lg'    => 'text-subhead md:text-headline',
        'sm'    => 'text-sm',
        default => 'text-base md:text-lg',
    };

    $thumbSize = match($size) {
        'lg' => 'w-40 h-24',
        'sm' => 'w-20 h-16',
        default => 'w-28 h-20',
    };
@endphp

@if($layout === 'horizontal')
<article class="group flex gap-3 {{ $attributes->get('class') }}">
@if($showImage)
    <a href="{{ route('article.show', $article->slug) }}" class="block shrink-0 {{ $thumbSize }} bg-[#f0f0f0] overflow-hidden relative">
        @if($article->has_video ?? false)
            @include('partials.youtube-embed', ['videoUrl' => $article->video_url, 'mode' => 'thumb'])
        @elseif($article->featured_image)
            <img src="{{ $article->featured_image_url }}" alt="" loading="lazy" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
        @else
            <div class="w-full h-full bg-[#e0e0e0]"></div>
        @endif
    </a>
@endif
    <div class="flex-1 min-w-0">
        @if($showTag && $article->category)
        <a href="{{ route('article.category', $article->category->slug) }}" class="text-[10px] font-bold uppercase tracking-widest text-[#E02020] hover:text-[#111] transition-colors">{{ $article->category->name_bn }}</a>
        @endif
        <a href="{{ route('article.show', $article->slug) }}" class="block mt-0.5">
            <h3 class="font-bold {{ $titleClass }} text-[#111] leading-snug group-hover:text-[#E02020] transition-colors duration-200 line-clamp-3">{{ $article->title_bn }}</h3>
        </a>
        @if($showTime)
        <p class="text-[10px] text-[#999] mt-1.5">
            @if($showAuthor && $article->staffs->isNotEmpty())<span class="text-[#666]">{{ $article->staffs->first()->name_bn }}</span> · @endif
            <time datetime="{{ $article->published_at?->toIso8601String() }}">{{ $article->published_at?->diffForHumans() }}</time>
        </p>
        @endif
    </div>
</article>
@else
<article class="group {{ $attributes->get('class') }}">
    @if($showImage)
    <a href="{{ route('article.show', $article->slug) }}" class="block {{ $imageClass }} bg-[#f0f0f0] overflow-hidden mb-3 relative">
        @if($article->has_video ?? false)
            @include('partials.youtube-embed', ['videoUrl' => $article->video_url, 'mode' => 'thumb'])
        @elseif($article->featured_image)
            <img src="{{ $article->featured_image_url }}" alt="{{ $article->title_bn }}" loading="lazy" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
        @else
            <div class="w-full h-full flex items-center justify-center text-[#ccc]">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        @endif
    </a>
    @endif
    @if($showTag && $article->category)
    <a href="{{ route('article.category', $article->category->slug) }}" class="text-[10px] font-bold uppercase tracking-widest text-[#E02020] hover:text-[#111] transition-colors">{{ $article->category->name_bn }}</a>
    @endif
    <a href="{{ route('article.show', $article->slug) }}" class="block mt-1">
        <h3 class="font-bold {{ $titleClass }} text-[#111] leading-snug group-hover:text-[#E02020] transition-colors duration-200 line-clamp-3">{{ $article->title_bn }}</h3>
    </a>
    @if($showSummary && $article->excerpt_bn)
    <p class="text-sm text-[#666] mt-2 leading-relaxed line-clamp-2">{{ $article->excerpt_bn }}</p>
    @endif
    @if($showTime)
    <p class="text-[10px] text-[#999] mt-2 flex items-center gap-1">
        @if($showAuthor && $article->staffs->isNotEmpty())<span class="text-[#666]">{{ $article->staffs->first()->name_bn }}</span><span>·</span>@endif
        <time datetime="{{ $article->published_at?->toIso8601String() }}">{{ $article->published_at?->diffForHumans() }}</time>
    </p>
    @endif
</article>
@endif
