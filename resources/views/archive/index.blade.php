@extends('layouts.app')

@section('title', 'আর্কাইভ — ' . config('app.name'))

@section('content')
<div class="max-w-[1240px] mx-auto px-4 py-6">
    <header class="border-b-2 border-[#111] pb-3 mb-6">
        <h1 class="font-serif font-black text-3xl text-[#111]">আর্কাইভ</h1>
        <p class="text-sm text-[#666] mt-1">সার্কুলার, গেজেট নোটিফিকেশন, নীতি নির্ধারণ দলিল ও অন্যান্য গুরুত্বপূর্ণ নথি</p>
    </header>

    <form method="GET" action="{{ route('archive.index') }}" class="mb-8 bg-white border border-[#e5e5e5] p-5 flex flex-wrap gap-3">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="নথি অনুসন্ধান..."
               class="flex-1 min-w-[200px] border border-[#e5e5e5] focus:border-[#111] focus:outline-none focus:ring-1 focus:ring-[#111] px-4 py-2.5 text-sm">
        <select name="year" class="border border-[#e5e5e5] focus:border-[#111] focus:outline-none focus:ring-1 focus:ring-[#111] px-4 py-2.5 text-sm bg-white">
            <option value="">সব বছর</option>
            @foreach($years as $year)
            <option value="{{ $year }}" @selected(request('year') == $year)>{{ $year }}</option>
            @endforeach
        </select>
        <select name="subcategory" class="border border-[#e5e5e5] focus:border-[#111] focus:outline-none focus:ring-1 focus:ring-[#111] px-4 py-2.5 text-sm bg-white">
            <option value="">সব বিষয়</option>
            @foreach($subcategories as $sub)
            <option value="{{ $sub }}" @selected(request('subcategory') == $sub)>{{ $sub }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-[#111] text-white px-6 py-2.5 text-sm font-semibold uppercase tracking-wider hover:bg-black transition">অনুসন্ধান</button>
    </form>

    @if($documents->isEmpty())
        <div class="text-center py-20 border border-[#e5e5e5]">
            <svg class="h-12 w-12 text-[#ccc] mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <p class="text-sm text-[#666]">কোনো নথি পাওয়া যায়নি।</p>
        </div>
    @else
    <div class="space-y-3">
        @foreach($documents as $doc)
        <article class="bg-white border border-[#e5e5e5] hover:border-[#111] p-5 flex items-start gap-4 transition">
            <div class="w-12 h-12 shrink-0 bg-[#f5f5f5] flex items-center justify-center text-[#666]">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="font-serif font-bold text-lg text-[#111]">{{ $doc->title_bn }}</h3>
                @if($doc->description_bn)
                <p class="text-sm text-[#666] mt-1">{{ Str::limit($doc->description_bn, 200) }}</p>
                @endif
                <div class="flex items-center gap-3 text-xs text-[#666] mt-2">
                    <span class="font-semibold">{{ $doc->year }}</span>
                    <span>•</span>
                    <span class="bg-[#111] text-white px-2 py-0.5 text-[10px] uppercase tracking-wider font-bold">{{ $doc->subcategory }}</span>
                    @if($doc->file_size)
                    <span>•</span>
                    <span>{{ round($doc->file_size / 1024, 1) }} KB</span>
                    @endif
                </div>
            </div>
            <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank"
               class="shrink-0 bg-[#111] text-white px-5 py-2 text-sm font-semibold uppercase tracking-wider hover:bg-black transition flex items-center gap-2">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                ডাউনলোড
            </a>
        </article>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $documents->withQueryString()->links('vendor.pagination.tailwind') }}
    </div>
    @endif
</div>
@endsection
