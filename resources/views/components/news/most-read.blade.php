@props([
    'items' => [],
    'title' => 'সর্বাধিক পঠিত',
    'limit' => 10,
])

@php
    $items = collect($items)->take($limit);
@endphp

@if($items->isNotEmpty())
<section>
    <div class="flex items-center gap-3 mb-4">
        <div class="w-1 h-5 bg-[#E02020] rounded-full shrink-0"></div>
        <h2 class="font-serif font-black text-lg text-[#111]">{{ $title }}</h2>
    </div>
    <div class="border-b-2 border-[#111] mb-4"></div>
    <ol class="space-y-3">
        @foreach($items as $i => $story)
        <li class="flex gap-3 {{ !$loop->last ? 'pb-3 border-b border-[#f0f0f0]' : '' }}">
            <span class="font-serif font-black text-xl {{ $i < 3 ? 'text-[#E02020]' : 'text-[#ccc]' }} leading-none w-6 shrink-0 mt-0.5">{{ str_pad($i+1, 2, '0', STR_PAD_LEFT) }}</span>
            <div class="flex-1 min-w-0">
                @if($story->category)
                <a href="{{ route('article.category', $story->category->slug) }}" class="text-[10px] font-bold uppercase tracking-widest text-[#E02020] hover:text-[#111] transition-colors">{{ $story->category->name_bn }}</a>
                @endif
                <a href="{{ route('article.show', $story->slug) }}" class="group block">
                    <h4 class="font-semibold {{ $i < 3 ? 'text-sm' : 'text-xs' }} text-[#111] leading-snug group-hover:text-[#E02020] transition-colors duration-200 line-clamp-3 mt-0.5">{{ $story->title_bn }}</h4>
                </a>
                <p class="text-[10px] text-[#999] mt-0.5">{{ $story->published_at?->diffForHumans() }}</p>
            </div>
        </li>
        @endforeach
    </ol>
</section>
@endif
