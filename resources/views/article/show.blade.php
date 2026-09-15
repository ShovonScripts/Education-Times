@extends('layouts.app')

@section('title', $article->meta_title ?? $article->title_bn . ' — ' . config('app.name'))
@section('meta_description', $article->meta_description ?? Str::limit(strip_tags($article->excerpt_bn ?? $article->body_bn), 160))
@section('og_type', 'article')
@unless($article->indexable)
    @push('meta')
    <meta name="robots" content="noindex, nofollow">
    @endpush
@endunless
@section('canonical', $article->canonical_url ?: route('article.show', $article->slug))
@php
    $imageUrl = null;
    foreach ([$article->og_image, $article->featured_image] as $candidate) {
        if ($candidate) {
            $imageUrl = \Illuminate\Support\Str::startsWith($candidate, ['http://', 'https://', 'data:'])
                ? $candidate
                : url(\Illuminate\Support\Facades\Storage::url($candidate));
            break;
        }
    }
    if (! $imageUrl) {
        $defaultImage = \App\Models\Setting::get('default_og_image') ?: \App\Models\Setting::get('site_logo');
        if ($defaultImage) {
            $imageUrl = \Illuminate\Support\Str::startsWith($defaultImage, ['http://', 'https://', 'data:'])
                ? $defaultImage
                : url(\Illuminate\Support\Facades\Storage::url($defaultImage));
        }
    }
@endphp
@if($imageUrl)
    @section('og_image', $imageUrl)
@endif
@section('structured_data')
@if($article->published_at)
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@type": "NewsArticle",
    "headline": {!! json_encode($article->title_bn) !!},
    "description": {!! json_encode(Str::limit(strip_tags($article->excerpt_bn ?? $article->body_bn), 160)) !!},
    "image": {!! json_encode($imageUrl) !!},
    "datePublished": {!! json_encode($article->published_at->toIso8601String()) !!},
    "dateModified": {!! json_encode($article->updated_at->toIso8601String()) !!},
    "author": {
        "@type": "Person",
        "name": {!! json_encode($article->author->name ?? config('app.name')) !!}
    },
    "publisher": {
        "@type": "Organization",
        "name": {!! json_encode(config('app.name')) !!},
        "logo": {
            "@type": "ImageObject",
            "url": {!! json_encode(asset('favicon.ico')) !!}
        }
    }
}
</script>
@endif
@endsection

@section('content')

@php
    use Carbon\Carbon;
    $publishedBn = $article->published_at?->locale('bn')->translatedFormat('l, j F Y, h:i A');
@endphp

<div class="max-w-[1240px] mx-auto px-4 py-6">

    {{-- Breadcrumb --}}
    <nav class="text-xs text-[#666] mb-3 flex items-center gap-1.5 flex-wrap">
        <a href="{{ route('home') }}" class="hover:text-[#111] hover:underline">প্রথম পাতা</a>
        <span class="text-[#999]">›</span>
        @if($article->category)
        <a href="{{ route('article.category', $article->category->slug) }}" class="hover:text-[#111] hover:underline">{{ $article->category->name_bn }}</a>
        <span class="text-[#999]">›</span>
        @endif
        <span class="text-[#111] truncate max-w-[60ch]">{{ $article->title_bn }}</span>
    </nav>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-8">

        {{-- Main article column --}}
        <article class="md:col-span-8 md:border-r md:border-[#e5e5e5] md:pr-8">
            {{-- Category & Date --}}
            <div class="flex items-center gap-3 text-xs text-[#666] mb-4 pb-4 border-b border-[#f0f0f0]">
                @if($article->category)
                <a href="{{ route('article.category', $article->category->slug) }}" class="bg-[#E02020] text-white px-3 py-1 text-[10px] font-bold uppercase tracking-widest hover:bg-[#c01818] transition-colors">{{ $article->category->name_bn }}</a>
                @endif
                <time datetime="{{ $article->published_at?->toIso8601String() }}" class="text-[#888]">{{ $publishedBn }}</time>
            </div>

            {{-- Headline --}}
            <h1 class="font-serif font-black text-2xl md:text-4xl leading-tight text-[#111] mb-3">
                {{ $article->title_bn }}
            </h1>

            {{-- Excerpt --}}
            @if($article->excerpt_bn)
            <p class="text-lg md:text-xl text-[#444] leading-relaxed font-serif border-l-4 border-[#E02020] pl-4 my-6 bg-[#fafafa] py-3 pr-4">
                {{ $article->excerpt_bn }}
            </p>
            @endif

            {{-- Byline & Share --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-y border-[#f0f0f0] py-4 my-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-[#E02020] text-white flex items-center justify-center font-serif font-bold text-sm shrink-0">
                        @if($article->staffs->isNotEmpty())
                            {{ mb_substr($article->staffs->first()->name_bn, 0, 1) }}
                        @elseif($article->author)
                            {{ mb_substr($article->author->name, 0, 1) }}
                        @else
                            ই
                        @endif
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#111]">
                            @if($article->staffs->isNotEmpty())
                            <a href="{{ route('staff.articles', $article->staffs->first()) }}" class="hover:text-[#E02020] transition-colors">{{ $article->staffs->first()->name_bn }}</a>
                            <span class="text-[#999] font-normal text-xs block">{{ $article->staffs->first()->designation_bn ?? 'প্রতিবেদক' }}</span>
                            @elseif($article->author)
                            {{ $article->author->name }}
                            @else
                            Education Times
                            @endif
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-1.5">
                    @php
                        $canInteract = auth()->check();
                        $likeCount = $article->likedByUsers()->count();
                    @endphp
                    {{-- Like --}}
                    <button type="button" id="likeBtn" data-url="{{ route('profile.like.toggle', $article) }}" data-auth="{{ $canInteract ? 1 : 0 }}"
                            class="flex items-center gap-1.5 h-8 px-3 rounded-full bg-[#f0f0f0] text-[#555] hover:bg-[#E02020] hover:text-white transition-all duration-200 {{ $liked ? 'bg-[#E02020] text-white' : '' }}" title="পছন্দ করুন">
                        <svg class="h-3.5 w-3.5" fill="{{ $liked ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        <span id="likeCount" class="text-xs font-semibold">{{ $likeCount }}</span>
                    </button>
                    {{-- Save --}}
                    <button type="button" id="saveBtn" data-url="{{ route('article.save.toggle', $article) }}" data-auth="{{ $canInteract ? 1 : 0 }}"
                            class="flex items-center gap-1.5 h-8 px-3 rounded-full bg-[#f0f0f0] text-[#555] hover:bg-[#111] hover:text-white transition-all duration-200 {{ $saved ? 'bg-[#111] text-white' : '' }}" title="পরে পড়ার জন্য সংরক্ষণ করুন">
                        <svg class="h-3.5 w-3.5" fill="{{ $saved ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                        <span id="saveLabel" class="text-xs font-semibold">{{ $saved ? 'সংরক্ষিত' : 'সংরক্ষণ' }}</span>
                    </button>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#999] mr-1 ml-2">শেয়ার:</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" rel="noopener" class="w-8 h-8 rounded-full bg-[#f0f0f0] text-[#555] hover:bg-[#1877F2] hover:text-white flex items-center justify-center transition-all duration-200" title="Facebook">
                        <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($article->title_bn) }}" target="_blank" rel="noopener" class="w-8 h-8 rounded-full bg-[#f0f0f0] text-[#555] hover:bg-[#111] hover:text-white flex items-center justify-center transition-all duration-200" title="X">
                        <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($article->title_bn . ' ' . request()->url()) }}" target="_blank" rel="noopener" class="w-8 h-8 rounded-full bg-[#f0f0f0] text-[#555] hover:bg-[#25D366] hover:text-white flex items-center justify-center transition-all duration-200" title="WhatsApp">
                        <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    </a>
                    <button type="button" onclick="window.print()" class="w-8 h-8 rounded-full bg-[#f0f0f0] text-[#555] hover:bg-[#111] hover:text-white flex items-center justify-center transition-all duration-200" title="Print">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    </button>
                </div>
            </div>

            {{-- Featured Image --}}
            @if($article->featured_image)
            <figure class="my-5 overflow-hidden">
                <img src="{{ $article->featured_image_url }}" alt="{{ $article->title_bn }}" class="w-full max-h-[520px] object-cover" loading="eager">
                @if($article->featured_image_caption)
                <figcaption class="text-xs text-[#666] mt-2 italic">{{ $article->featured_image_caption }}@if($article->photo_credit) — {{ $article->photo_credit }}@endif</figcaption>
                @endif
            </figure>
            @endif

            {{-- Article Body --}}
            <div class="prose-bn">
                @if($article->has_video)
                    <div class="my-5 aspect-video">
                        @include('partials.youtube-embed', ['videoUrl' => $article->video_url, 'mode' => 'embed'])
                    </div>
                @endif
                {!! $article->body_bn !!}
            </div>

            {{-- Tags --}}
            @if($article->tags->isNotEmpty())
            <div class="mt-8 pt-5 border-t border-[#e5e5e5]">
                <p class="text-xs font-bold uppercase tracking-wider text-[#111] mb-2">বিষয়:</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($article->tags as $tag)
                    <a href="{{ route('search.index', ['q' => $tag->tag]) }}" class="text-xs px-2.5 py-1 border border-[#111] text-[#111] hover:bg-[#111] hover:text-white transition">{{ $tag->tag }}</a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Author Bio --}}
            @if($article->staffs->isNotEmpty())
            <div class="mt-8 bg-[#f5f5f5] p-5 border-l-4 border-[#111]">
                <p class="text-xs font-bold uppercase tracking-wider text-[#111] mb-2">প্রতিবেদক</p>
                <h4 class="font-serif font-black text-lg text-[#111]">{{ $article->staffs->first()->name_bn }}</h4>
                <p class="text-xs text-[#666] mb-2">{{ $article->staffs->first()->designation_bn ?? '' }}</p>
                @if($article->staffs->first()->bio_bn)
                <p class="text-sm text-[#555] leading-relaxed">{{ $article->staffs->first()->bio_bn }}</p>
                @endif
            </div>
            @endif

            {{-- Comments Section --}}
            <section class="mt-10 border-t-2 border-[#111] pt-8">
                <h2 class="font-serif font-black text-xl text-[#111] mb-6">মন্তব্য</h2>

                @if(session('success'))
                <div class="mb-6 border-l-4 border-[#111] bg-[#f5f5f5] px-4 py-3 text-sm">{{ session('success') }}</div>
                @endif

                @if(isset($comments) && $comments->isNotEmpty())
                <div class="space-y-6 mb-8">
                    @foreach($comments as $comment)
                    <div class="flex gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#111] text-white flex items-center justify-center font-serif font-bold text-sm shrink-0">
                            {{ mb_substr($comment->user->name ?? 'অ', 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <div class="bg-[#f5f5f5] p-4">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-sm font-semibold text-[#111]">{{ $comment->user->name ?? 'অনাম্য' }}</span>
                                    <span class="text-xs text-[#999]">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-[#444] leading-relaxed">{{ $comment->body }}</p>
                            </div>
                            @if($comment->replies->isNotEmpty())
                            <div class="mt-3 ml-4 space-y-3">
                                @foreach($comment->replies as $reply)
                                <div class="flex gap-3">
                                    <div class="w-8 h-8 rounded-full bg-[#e5e5e5] text-[#111] flex items-center justify-center font-serif font-bold text-xs shrink-0">
                                        {{ mb_substr($reply->user->name ?? 'অ', 0, 1) }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="bg-white border border-[#e5e5e5] p-3">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="text-xs font-semibold text-[#111]">{{ $reply->user->name ?? 'অনাম্য' }}</span>
                                                <span class="text-xs text-[#999]">{{ $reply->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-sm text-[#444] leading-relaxed">{{ $reply->body }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-sm text-[#666] mb-6">এখনো কোনো মন্তব্য নেই। প্রথম মন্তব্যকারী হন!</p>
                @endif

                @auth
                <div class="bg-white border border-[#e5e5e5] p-6">
                    <h3 class="font-serif font-black text-lg text-[#111] mb-4">মন্তব্য লিখুন</h3>
                    <form method="POST" action="{{ route('comments.store') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="article_id" value="{{ $article->id }}">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-[#111] mb-1.5">আপনার মন্তব্য</label>
                            <textarea name="body" rows="4" required class="w-full border border-[#e5e5e5] focus:border-[#111] focus:outline-none focus:ring-1 focus:ring-[#111] px-3 py-2.5 text-sm resize-y"></textarea>
                        </div>
                        <button type="submit" class="bg-[#111] hover:bg-black text-white text-sm font-semibold uppercase tracking-wider py-2.5 px-6 transition">মন্তব্য পোস্ট করুন</button>
                    </form>
                </div>
                @else
                <div class="bg-[#f5f5f5] border border-[#e5e5e5] p-6 text-center">
                    <p class="text-sm text-[#666] mb-3">মন্তব্য করতে লগইন করুন</p>
                    <a href="{{ route('login') }}" class="inline-block bg-[#111] hover:bg-black text-white text-sm font-semibold uppercase tracking-wider py-2.5 px-6 transition">লগইন করুন</a>
                </div>
                @endauth
            </section>

            {{-- Related Articles --}}
            @if(isset($related) && $related->isNotEmpty())
            <section class="mt-10">
                <header class="border-b-2 border-[#111] pb-2 mb-5">
                    <h2 class="font-serif font-black text-xl text-[#111]">আরও পড়ুন</h2>
                </header>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    @foreach($related as $rel)
                    <article class="group">
                        <a href="{{ route('article.show', $rel->slug) }}" class="block aspect-[16/9] bg-[#f5f5f5] overflow-hidden mb-2">
                            @if($rel->featured_image)
                                <img src="{{ $rel->featured_image_url }}" alt="" loading="lazy" class="w-full h-full object-cover group-hover:opacity-90 transition">
                            @else
                                <div class="w-full h-full bg-[#e5e5e5]"></div>
                            @endif
                        </a>
                        <a href="{{ route('article.show', $rel->slug) }}" class="block">
                            <h3 class="font-serif font-bold text-sm text-[#111] leading-snug group-hover:underline decoration-1 underline-offset-2 line-clamp-3">{{ $rel->title_bn }}</h3>
                        </a>
                    </article>
                    @endforeach
                </div>
            </section>
            @endif
        </article>

        {{-- Sidebar --}}
        <aside class="md:col-span-4 space-y-8">
            @include('partials.ads.sidebar')

            {{-- Related in same category --}}
            @if(isset($related) && $related->isNotEmpty())
            <section>
                <header class="border-b-2 border-[#111] pb-2 mb-4">
                    <h2 class="font-serif font-black text-lg text-[#111]">এই বিভাগে</h2>
                </header>
                <ul class="space-y-3">
                    @foreach($related->take(5) as $i => $rel)
                    <li class="flex gap-3 {{ !$loop->last ? 'pb-3 border-b border-[#e5e5e5]' : '' }}">
                        <span class="font-serif font-black text-xl text-[#111] leading-none w-6 shrink-0">{{ str_pad($i+1, 2, '0', STR_PAD_LEFT) }}</span>
                        <a href="{{ route('article.show', $rel->slug) }}" class="flex-1">
                            <h4 class="font-serif font-semibold text-sm text-[#111] leading-snug hover:underline decoration-1 underline-offset-2 line-clamp-3">{{ $rel->title_bn }}</h4>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </section>
            @endif
        </aside>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function() {
    function wire(btn, onDone) {
        if (!btn) return;
        btn.addEventListener('click', function() {
            if (btn.dataset.auth !== '1') {
                window.location.href = '{{ route('login') }}';
                return;
            }
            btn.disabled = true;
            fetch(btn.dataset.url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            })
            .then(function(r) { return r.json(); })
            .then(function(data) { btn.disabled = false; onDone(btn, data); })
            .catch(function() { btn.disabled = false; });
        });
    }

    wire(document.getElementById('likeBtn'), function(btn, data) {
        var count = document.getElementById('likeCount');
        var svg = btn.querySelector('svg');
        count.textContent = data.count;
        btn.classList.toggle('bg-[#E02020]', data.liked);
        btn.classList.toggle('text-white', data.liked);
        svg.setAttribute('fill', data.liked ? 'currentColor' : 'none');
    });

    wire(document.getElementById('saveBtn'), function(btn, data) {
        var label = document.getElementById('saveLabel');
        var svg = btn.querySelector('svg');
        label.textContent = data.saved ? 'সংরক্ষিত' : 'সংরক্ষণ';
        btn.classList.toggle('bg-[#111]', data.saved);
        btn.classList.toggle('text-white', data.saved);
        svg.setAttribute('fill', data.saved ? 'currentColor' : 'none');
    });
})();
</script>
@endpush
