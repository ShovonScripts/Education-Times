@props([
    'lead' => null,
    'secondary' => [],
    'list' => [],
])

<section class="border-b border-gray-100 mb-6 pb-6">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-0 lg:divide-x lg:divide-gray-100">

        {{-- ====================================================== --}}
        {{-- LEAD (6 cols) --}}
        {{-- ====================================================== --}}
        @if($lead)
        <article class="group lg:col-span-6 lg:pr-6 mb-6 lg:mb-0">
            <a href="{{ route('article.show', $lead->slug) }}" class="block aspect-[16/9] bg-gray-100 overflow-hidden mb-3 rounded-lg relative">
                @if($lead->has_video)
                    @include('partials.youtube-embed', ['videoUrl' => $lead->video_url, 'mode' => 'thumb'])
                @elseif($lead->featured_image)
                    <img src="{{ $lead->featured_image_url }}" alt="{{ $lead->title_bn }}" loading="eager" class="w-full h-full object-cover transition duration-700 group-hover:scale-105">
                @else
                    <div class="absolute inset-0 flex items-center justify-center bg-gray-100">
                        <svg class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                @endif
            </a>

            <div class="flex items-center gap-2 mb-1.5">
                @if($lead->category)
                <a href="{{ route('article.category', $lead->category->slug) }}" class="text-[10px] font-bold uppercase tracking-widest text-[#E02020] hover:text-black transition">{{ $lead->category->name_bn }}</a>
                <span class="text-gray-300">·</span>
                @endif
                <time datetime="{{ $lead->published_at?->toIso8601String() }}" class="text-[11px] text-gray-400 font-medium">{{ $lead->published_at?->diffForHumans() }}</time>
            </div>

            <a href="{{ route('article.show', $lead->slug) }}" class="block">
                <h2 class="font-serif font-black text-2xl md:text-3xl lg:text-[2rem] text-gray-900 leading-tight group-hover:text-[#E02020] transition-colors duration-200 line-clamp-3 mb-2">
                    {{ $lead->title_bn }}
                </h2>
            </a>

            @if($lead->excerpt_bn)
            <p class="text-sm text-gray-500 leading-relaxed line-clamp-2">{{ $lead->excerpt_bn }}</p>
            @endif
        </article>
        @endif

        {{-- ====================================================== --}}
        {{-- SECONDARY (3 cols) — clean text list, no images --}}
        {{-- ====================================================== --}}
        @if(count($secondary) > 0)
        <div class="hidden lg:flex lg:col-span-3 flex-col divide-y divide-gray-100 lg:px-5">
            @foreach($secondary as $i => $story)
            <article class="group {{ $i === 0 ? 'pb-3' : 'py-3' }}">
                <div class="flex items-start gap-3">
                    {{-- Number --}}
                    <span class="font-serif font-black text-[22px] leading-none shrink-0 mt-0.5 text-gray-200 group-hover:text-[#E02020] transition-colors duration-200">
                        {{ str_pad($i+1, 2, '0', STR_PAD_LEFT) }}
                    </span>
                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        @if($story->category)
                        <a href="{{ route('article.category', $story->category->slug) }}" class="text-[9px] font-bold uppercase tracking-widest text-[#E02020] hover:text-black transition">{{ $story->category->name_bn }}</a>
                        @endif
                        <a href="{{ route('article.show', $story->slug) }}" class="block mt-0.5">
                            <h3 class="font-bold text-sm text-gray-900 leading-snug group-hover:text-[#E02020] transition-colors duration-200 line-clamp-3">{{ $story->title_bn }}</h3>
                        </a>
                        <time datetime="{{ $story->published_at?->toIso8601String() }}" class="text-[10px] text-gray-400 mt-1 block">{{ $story->published_at?->diffForHumans() }}</time>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
        @endif

        {{-- ====================================================== --}}
        {{-- TRENDING SIDEBAR (3 cols) --}}
        {{-- ====================================================== --}}
        @if(count($list) > 0)
        <aside class="lg:col-span-3 flex flex-col lg:pl-5 border-t border-gray-100 pt-4 lg:pt-0 lg:border-t-0 mt-0">
            <div class="flex items-center gap-2 pb-2 mb-3 border-b-2 border-gray-900">
                <div class="w-1.5 h-1.5 rounded-full bg-[#E02020] animate-pulse"></div>
                <h3 class="text-[11px] font-extrabold uppercase tracking-widest text-gray-900">এই মুহূর্তে</h3>
            </div>
            <ul class="flex flex-col divide-y divide-gray-100">
                @foreach($list as $i => $story)
                <li class="flex gap-3 group py-2.5 {{ $loop->first ? 'pt-0' : '' }}">
                    <span class="font-serif font-black text-[20px] leading-none mt-0.5 shrink-0 w-7 {{ $i < 3 ? 'text-[#E02020]' : 'text-gray-200' }} group-hover:text-[#E02020] transition-colors">
                        {{ str_pad($i+1, 2, '0', STR_PAD_LEFT) }}
                    </span>
                    <a href="{{ route('article.show', $story->slug) }}" class="text-[13px] font-semibold text-gray-800 leading-snug group-hover:text-[#E02020] transition-colors duration-200 line-clamp-3">
                        {{ $story->title_bn }}
                    </a>
                </li>
                @endforeach
            </ul>
        </aside>
        @endif
    </div>
</section>
