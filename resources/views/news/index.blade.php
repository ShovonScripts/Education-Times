@extends('layouts.app')

@section('title', 'সব খবর — ' . config('app.name'))
@section('meta_description', 'বাংলাদেশের শিক্ষা সংবাদ — সর্বশেষ সব খবর একসাথে।')

@section('content')
<div class="max-w-[1240px] mx-auto px-4 py-6">

    {{-- ── Page Header ───────────────────────────────────────────── --}}
    <header class="mb-6 pb-5 border-b-2 border-gray-900">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
            <div>
                <h1 class="font-serif font-black text-3xl md:text-4xl text-gray-900 leading-tight">সব খবর</h1>
                <p class="text-sm text-gray-400 mt-1">সর্বশেষ শিক্ষা সংবাদ — কালানুক্রমিক তালিকা</p>
            </div>
            {{-- Article count --}}
            <div class="text-xs text-gray-400 font-medium">
                {{ number_format($articles->total()) }} টি সংবাদ
            </div>
        </div>
    </header>

    {{-- ── Category Filter Tabs ──────────────────────────────────── --}}
    <div class="mb-6 overflow-x-auto">
        <div class="flex gap-2 min-w-max pb-1">
            <a href="{{ route('news.index') }}"
               class="px-3 py-1.5 text-xs font-bold uppercase tracking-wider rounded-full transition-all
                      {{ !$selectedCategory ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                সব বিভাগ
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('news.index', ['category' => $cat->slug]) }}"
               class="px-3 py-1.5 text-xs font-bold uppercase tracking-wider rounded-full transition-all whitespace-nowrap
                      {{ $selectedCategory?->id === $cat->id ? 'bg-[#E02020] text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                {{ $cat->name_bn }}
            </a>
            @endforeach
        </div>
    </div>

    {{-- ── Timeline ──────────────────────────────────────────────── --}}
    @if($grouped->isEmpty())
        <div class="text-center py-24 text-gray-400">
            <svg class="h-12 w-12 mx-auto mb-3 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 12h6"/></svg>
            <p class="text-sm">কোনো সংবাদ পাওয়া যায়নি।</p>
        </div>
    @else
        <div class="relative">
            {{-- Vertical timeline line (desktop only) --}}
            <div class="hidden md:block absolute left-[88px] top-0 bottom-0 w-px bg-gray-100 z-0"></div>

            @foreach($grouped as $date => $dayArticles)
            <div class="mb-8">
                {{-- ── Date label ──────────────────────────────── --}}
                <div class="flex items-center gap-4 mb-4 sticky top-[60px] z-10 bg-white/90 backdrop-blur-sm py-1">
                    <div class="shrink-0 w-[80px] text-right">
                        @php
                            $d = \Carbon\Carbon::parse($date);
                            $today = now()->toDateString();
                            $yesterday = now()->subDay()->toDateString();
                        @endphp
                        @if($date === $today)
                            <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-widest text-[#E02020]">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#E02020] animate-pulse inline-block"></span>
                                আজ
                            </span>
                        @elseif($date === $yesterday)
                            <span class="text-[10px] font-bold uppercase tracking-widest text-gray-500">গতকাল</span>
                        @else
                            <div class="text-right">
                                <div class="text-base font-black text-gray-800 leading-none">{{ $d->format('d') }}</div>
                                <div class="text-[9px] font-bold uppercase tracking-wide text-gray-400">{{ $d->translatedFormat('M Y') }}</div>
                            </div>
                        @endif
                    </div>
                    {{-- Dot on the line --}}
                    <div class="hidden md:flex shrink-0 w-4 h-4 rounded-full border-2 border-gray-900 bg-white z-10 items-center justify-center">
                        <div class="w-1.5 h-1.5 rounded-full {{ $date === $today ? 'bg-[#E02020]' : 'bg-gray-900' }}"></div>
                    </div>
                    <div class="flex-1 h-px bg-gray-100 md:hidden"></div>
                </div>

                {{-- ── Articles for this day ────────────────────── --}}
                <div class="md:pl-[104px] space-y-0 divide-y divide-gray-50">
                    @foreach($dayArticles as $article)
                    <article class="group flex gap-3 md:gap-4 py-3 hover:bg-gray-50/60 -mx-2 px-2 rounded-lg transition-colors">

                        {{-- Time --}}
                        <div class="shrink-0 w-12 text-right pt-0.5">
                            <time datetime="{{ $article->published_at?->toIso8601String() }}"
                                  class="text-[10px] text-gray-400 font-medium tabular-nums">
                                {{ $article->published_at?->format('H:i') }}
                            </time>
                        </div>

                        {{-- Thumbnail --}}
                        @if($article->featured_image || $article->video_url)
                        <a href="{{ route('article.show', $article->slug) }}"
                           class="shrink-0 w-20 h-14 md:w-24 md:h-16 bg-gray-100 overflow-hidden rounded relative block">
                            @if($article->video_url)
                                @php
                                    $ytId = null;
                                    preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/|v\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $article->video_url, $ytM);
                                    $ytId = $ytM[1] ?? null;
                                @endphp
                                @if($ytId)
                                    <img src="https://img.youtube.com/vi/{{ $ytId }}/mqdefault.jpg" alt="" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    <div class="absolute inset-0 bg-black/20 flex items-center justify-center">
                                        <div class="w-6 h-6 rounded-full bg-[#E02020]/90 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                        </div>
                                    </div>
                                @endif
                            @elseif($article->featured_image)
                                <img src="{{ $article->featured_image_url }}" alt="" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @endif
                        </a>
                        @endif

                        {{-- Text --}}
                        <div class="flex-1 min-w-0">
                            @if($article->category)
                            <a href="{{ route('article.category', $article->category->slug) }}"
                               class="text-[9px] font-bold uppercase tracking-widest text-[#E02020] hover:text-gray-900 transition">
                                {{ $article->category->name_bn }}
                            </a>
                            @endif
                            <a href="{{ route('article.show', $article->slug) }}" class="block mt-0.5">
                                <h2 class="font-bold text-sm md:text-base text-gray-900 leading-snug group-hover:text-[#E02020] transition-colors duration-200 line-clamp-2 md:line-clamp-3">
                                    {{ $article->title_bn }}
                                </h2>
                            </a>
                            @if($article->staffs->isNotEmpty())
                            <p class="text-[10px] text-gray-400 mt-1">{{ $article->staffs->first()->name_bn }}</p>
                            @endif
                        </div>
                    </article>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        {{-- ── Pagination ────────────────────────────────────────── --}}
        @if($articles->hasPages())
        <div class="mt-10 pt-6 border-t border-gray-100">
            {{ $articles->withQueryString()->links('vendor.pagination.tailwind') }}
        </div>
        @endif
    @endif

</div>
@endsection
