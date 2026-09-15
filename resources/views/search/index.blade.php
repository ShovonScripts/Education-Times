@extends('layouts.app')

@section('title', 'অনুসন্ধান: ' . request('q', '') . ' — ' . config('app.name'))
@section('robots', 'noindex, follow')

@section('content')
<div class="max-w-[1240px] mx-auto px-4 py-6">
    <header class="border-b-2 border-[#111] pb-3 mb-6">
        <h1 class="font-serif font-black text-2xl md:text-3xl text-[#111]">অনুসন্ধান</h1>
        <p class="text-sm text-[#666] mt-1">আপনি যা খুঁজছেন তা এখানে লিখুন</p>
    </header>

    <form action="{{ route('search.index') }}" method="GET" class="mb-8 flex items-stretch max-w-2xl">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="খবর খুঁজুন..."
               class="flex-1 border border-[#111] px-4 py-3 text-sm focus:outline-none focus:ring-1 focus:ring-[#111]">
        <button type="submit" class="bg-[#111] text-white px-6 py-3 text-sm font-semibold uppercase tracking-wider hover:bg-black transition">খুঁজুন</button>
    </form>

    @if(request('q'))
        <p class="text-sm text-[#666] mb-6">
            <strong class="text-[#111]">"{{ request('q') }}"</strong> এর জন্য {{ $articles->total() ?? 0 }} টি ফলাফল পাওয়া গেছে
        </p>

        @if(($articles ?? collect())->isEmpty())
        <div class="text-center py-20 border border-[#e5e5e5]">
            <svg class="h-12 w-12 text-[#ccc] mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <p class="text-sm text-[#666]">আপনার অনুসন্ধানের সাথে মিলে এমন কোনো সংবাদ পাওয়া যায়নি।</p>
            <p class="text-xs text-[#888] mt-2">অন্য কীওয়ার্ড দিয়ে চেষ্টা করুন।</p>
        </div>
        @else
        <div class="space-y-6">
            @foreach($articles as $article)
            <article class="flex gap-5 pb-6 border-b border-[#e5e5e5]">
                <a href="{{ route('article.show', $article->slug) }}" class="block shrink-0 w-40 h-24 bg-[#f5f5f5] overflow-hidden">
                    @if($article->featured_image)
                        <img src="{{ $article->featured_image_url }}" alt="" loading="lazy" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-[#e5e5e5]"></div>
                    @endif
                </a>
                <div class="flex-1 min-w-0">
                    @if($article->category)
                    <a href="{{ route('article.category', $article->category->slug) }}" class="text-[10px] font-bold uppercase tracking-widest text-[#111] border-l-2 border-[#111] pl-1.5">{{ $article->category->name_bn }}</a>
                    @endif
                    <a href="{{ route('article.show', $article->slug) }}" class="block">
                        <h3 class="font-serif font-bold text-lg text-[#111] leading-snug hover:underline decoration-1 underline-offset-2">{{ $article->title_bn }}</h3>
                    </a>
                    @if($article->excerpt_bn)
                    <p class="text-sm text-[#555] mt-1.5 line-clamp-2">{{ $article->excerpt_bn }}</p>
                    @endif
                    <p class="text-xs text-[#888] mt-2">{{ $article->published_at?->diffForHumans() }}</p>
                </div>
            </article>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $articles->links('vendor.pagination.tailwind') }}
        </div>
        @endif
    @endif
</div>
@endsection
