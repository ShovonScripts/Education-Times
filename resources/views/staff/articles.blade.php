@extends('layouts.app')

@section('title', $staff->name_bn . ' - ' . config('app.name'))

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="mb-8 pb-6 border-b border-[#e0e0e0] dark:border-[#333]">
        <div class="flex items-center gap-4">
            @if($staff->photo)
            <div class="w-16 h-16 md:w-20 md:h-20 rounded-full overflow-hidden bg-[#0d0d0d] shrink-0">
                <img src="{{ $staff->photo }}" alt="" class="w-full h-full object-cover">
            </div>
            @else
            <div class="w-16 h-16 md:w-20 md:h-20 rounded-full bg-[#0d0d0d] dark:bg-[#1a1a1a] flex items-center justify-center shrink-0 border-2 border-[#E02020]">
                <span class="text-xl md:text-2xl font-bold text-[#E02020]">{{ mb_substr($staff->name_bn, 0, 1) }}</span>
            </div>
            @endif
            <div>
                <h1 class="text-xl md:text-3xl font-bold dark:text-white">
                    <span class="inline-block w-1 h-5 bg-[#E02020] align-middle mr-2"></span>
                    {{ $staff->name_bn }}
                </h1>
                <p class="text-[#666] dark:text-[#999] mt-1">{{ $staff->designation_bn }}</p>
                @if($staff->bio_bn)
                <p class="text-sm text-[#666] dark:text-[#999] mt-2 max-w-xl">{{ $staff->bio_bn }}</p>
                @endif
            </div>
        </div>
        <p class="text-xs text-[#999] dark:text-[#777] mt-4">{{ $articles->total() }} টি প্রকাশিত সংবাদ</p>
    </div>

    @if($articles->isEmpty())
        <div class="text-center py-16 bg-white dark:bg-[#1e1e1e] border border-[#e0e0e0] dark:border-[#333]">
            <svg class="h-12 w-12 text-[#ccc] dark:text-[#444] mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            <p class="text-sm text-[#999] dark:text-[#777]">এই প্রতিবেদকের কোনো সংবাদ পাওয়া যায়নি।</p>
        </div>
    @else
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($articles as $article)
                <a href="{{ route('article.show', $article->slug) }}" class="group block bg-white dark:bg-[#1e1e1e] border border-[#e0e0e0] dark:border-[#333] shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 {{ $loop->first ? 'md:col-span-2 md:grid md:grid-cols-2 md:gap-0' : '' }}">
                    <div class="aspect-[16/9] {{ $loop->first ? 'md:aspect-auto md:h-full md:min-h-[280px]' : '' }} bg-[#0d0d0d] dark:bg-[#1a1a1a] overflow-hidden">
                        @if($article->has_video)
                        @include('partials.youtube-embed', ['videoUrl' => $article->video_url, 'mode' => 'thumb'])
                        @elseif($article->featured_image)
                        <img src="{{ $article->featured_image_url }}" alt="" class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                        @endif
                    </div>
                    <div class="p-3 {{ $loop->first ? 'flex flex-col justify-center' : '' }}">
                        @if($article->category)
                        <span class="text-xs font-bold uppercase tracking-widest text-[#E02020]">{{ $article->category->name_bn }}</span>
                        @endif
                        <h2 class="text-base md:text-lg font-bold leading-snug mt-0.5 group-hover:text-[#E02020] transition-colors line-clamp-2">
                            {{ $article->title_bn }}
                        </h2>
                        @if($article->excerpt_bn)
                        <p class="text-sm text-[#666] dark:text-[#999] mt-1 leading-relaxed line-clamp-2">{{ Str::limit($article->excerpt_bn, $loop->first ? 200 : 100) }}</p>
                        @endif
                        <p class="text-xs text-[#999] dark:text-[#777] mt-2">{{ $article->published_at?->diffForHumans() }}</p>
                    </div>
                </a>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $articles->links() }}
            </div>
        </div>

        <aside class="lg:col-span-4 space-y-6">
            @include('partials.ads.sidebar')
        </aside>
    </div>
    @endif
</div>
@endsection
