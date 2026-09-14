@extends('layouts.app')

@section('title', $category->name_bn . ' — ' . config('app.name'))
@section('meta_description', $category->description ?? ($category->name_bn . ' বিভাগের সর্বশেষ সংবাদ'))
@section('canonical', route('article.category', $category->slug))
@section('structured_data')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@type": "CollectionPage",
    "name": {!! json_encode($category->name_bn) !!},
    "description": {!! json_encode($category->description ?? $category->name_bn . ' বিভাগের সর্বশেষ সংবাদ') !!},
    "url": {!! json_encode(route('article.category', $category->slug)) !!}
}
</script>
@endsection

@section('content')
<div class="max-w-[1240px] mx-auto px-4 py-6">

    {{-- Breadcrumb --}}
    <nav class="text-xs text-[#666] mb-3 flex items-center gap-1.5">
        <a href="{{ route('home') }}" class="hover:text-[#111] hover:underline">প্রথম পাতা</a>
        <span class="text-[#999]">›</span>
        <span class="text-[#111]">{{ $category->name_bn }}</span>
    </nav>

    {{-- Category Header --}}
    <header class="border-b-2 border-[#111] pb-3 mb-6 flex items-end justify-between flex-wrap gap-3">
        <div>
            <h1 class="font-serif font-black text-3xl md:text-4xl text-[#111]">{{ $category->name_bn }}</h1>
            @if($category->description)
            <p class="text-sm text-[#666] mt-2 max-w-2xl">{{ $category->description }}</p>
            @endif
        </div>
        <p class="text-xs text-[#666] uppercase tracking-wider font-semibold">{{ $articles->total() }} টি সংবাদ</p>
    </header>

    @if($articles->isEmpty())
        <div class="text-center py-20 border border-[#e5e5e5]">
            <svg class="h-12 w-12 text-[#ccc] mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2"/></svg>
            <p class="text-sm text-[#666]">এই বিভাগে এখনো কোনো সংবাদ প্রকাশিত হয়নি।</p>
        </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
        <div class="md:col-span-8">
            {{-- First item: featured --}}
            @php $first = $articles->first(); @endphp
            <article class="mb-8 pb-8 border-b-2 border-[#111]">
                <a href="{{ route('article.show', $first->slug) }}" class="block group">
                    <div class="aspect-[16/9] bg-[#f5f5f5] overflow-hidden mb-4 relative">
                        @if($first->has_video)
                            @include('partials.youtube-embed', ['videoUrl' => $first->video_url, 'mode' => 'thumb'])
                        @elseif($first->featured_image)
                            <img src="{{ $first->featured_image_url }}" alt="{{ $first->title_bn }}" loading="eager" class="w-full h-full object-cover group-hover:opacity-90 transition">
                        @else
                            <div class="w-full h-full bg-[#e5e5e5]"></div>
                        @endif
                    </div>
                    <h2 class="font-serif font-black text-2xl md:text-3xl text-[#111] leading-tight group-hover:underline decoration-2 underline-offset-4">{{ $first->title_bn }}</h2>
                    @if($first->excerpt_bn)
                    <p class="text-[#555] mt-3 line-clamp-2">{{ $first->excerpt_bn }}</p>
                    @endif
                    <p class="text-xs text-[#888] mt-3">
                        @if($first->staffs->isNotEmpty()){{ $first->staffs->first()->name_bn }} · @endif
                        {{ $first->published_at?->diffForHumans() }}
                    </p>
                </a>
            </article>

            {{-- Rest as grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-7">
                @foreach($articles->skip(1) as $article)
                <article class="group">
                    <a href="{{ route('article.show', $article->slug) }}" class="block aspect-[16/9] bg-[#f5f5f5] overflow-hidden mb-3 relative">
                        @if($article->has_video)
                            @include('partials.youtube-embed', ['videoUrl' => $article->video_url, 'mode' => 'thumb'])
                        @elseif($article->featured_image)
                            <img src="{{ $article->featured_image_url }}" alt="" loading="lazy" class="w-full h-full object-cover group-hover:opacity-90 transition">
                        @else
                            <div class="w-full h-full bg-[#e5e5e5]"></div>
                        @endif
                    </a>
                    <a href="{{ route('article.show', $article->slug) }}" class="block">
                        <h3 class="font-serif font-bold text-lg text-[#111] leading-snug group-hover:underline decoration-1 underline-offset-2 line-clamp-3">{{ $article->title_bn }}</h3>
                    </a>
                    <p class="text-xs text-[#888] mt-2">{{ $article->published_at?->diffForHumans() }}</p>
                </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-10">
                {{ $articles->links('vendor.pagination.tailwind') }}
            </div>
        </div>

        <aside class="md:col-span-4 space-y-8">
            @include('partials.ads.sidebar')

            {{-- Other categories --}}
            @if(isset($navCategories) && $navCategories->isNotEmpty())
            <section>
                <header class="border-b-2 border-[#111] pb-2 mb-3">
                    <h3 class="font-serif font-black text-lg text-[#111]">অন্যান্য বিভাগ</h3>
                </header>
                <ul class="space-y-1.5">
                    @foreach($navCategories as $cat)
                        @if($cat->id !== $category->id)
                        <li>
                            <a href="{{ route('article.category', $cat->slug) }}" class="block px-3 py-2 text-sm font-semibold text-[#444] hover:bg-[#111] hover:text-white border-l-2 border-transparent hover:border-[#111] transition">{{ $cat->name_bn }}</a>
                        </li>
                        @endif
                    @endforeach
                </ul>
            </section>
            @endif

            @include('partials.ads.sidebar')
        </aside>
    </div>
    @endif
</div>

@endsection
