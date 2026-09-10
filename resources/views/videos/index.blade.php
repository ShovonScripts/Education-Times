@extends('layouts.app')
@section('title', 'ভিডিও গ্যালারি — ' . config('app.name'))
@section('meta_description', 'Education Times এর সকল ভিডিও সংবাদ, সাক্ষাৎকার ও বিশেষ প্রতিবেদন।')

@section('content')
<div class="max-w-[1240px] mx-auto px-4 py-6">
    <header class="border-b-2 border-[#111] pb-3 mb-6 flex items-end justify-between">
        <div>
            <h1 class="font-serif font-black text-3xl text-[#111]">ভিডিও গ্যালারি</h1>
            <p class="text-sm text-[#666] mt-1">শিক্ষা বিষয়ক সকল ভিডিও সংবাদ, সাক্ষাৎকার ও প্রতিবেদন</p>
        </div>
        <p class="text-xs text-[#666] uppercase tracking-wider font-semibold">{{ $videos->total() }} টি ভিডিও</p>
    </header>

    @if($videos->isEmpty())
        <div class="text-center py-20 border border-[#e5e5e5]">
            <svg class="h-12 w-12 text-[#ccc] mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
            <p class="text-sm text-[#666]">এখনো কোনো ভিডিও প্রকাশিত হয়নি।</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-5 gap-y-7">
            @foreach($videos as $video)
                <x-news.video-card :article="$video" />
            @endforeach
        </div>

        <div class="mt-10">
            {{ $videos->links('vendor.pagination.tailwind') }}
        </div>
    @endif
</div>
@endsection
