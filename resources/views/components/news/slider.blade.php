@props(['articles'])

@if($articles->isNotEmpty())
    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
        <style>
            /* ─── Container ─────────────────────────────────── */
            .hero-swiper {
                width: 100%;
                height: 340px;
                border-radius: 12px;
                overflow: hidden;
                background: #111;
                box-shadow: 0 2px 20px rgba(0,0,0,0.14);
            }
            @media (min-width: 768px)  { .hero-swiper { height: 420px; } }
            @media (min-width: 1024px) { .hero-swiper { height: 490px; } }

            /* ─── Slide base ─────────────────────────────────── */
            .hero-swiper .swiper-slide {
                position: relative;
                width: 100%;
                height: 100%;
                overflow: hidden;
            }

            /* ─── Image slides ───────────────────────────────── */
            .hero-swiper .slide-bg-img {
                position: absolute;
                inset: 0;
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: center 20%;
                z-index: 0;
                transition: transform 8s ease;
                display: block;
            }
            .hero-swiper .swiper-slide-active .slide-bg-img {
                transform: scale(1.05);
            }

            /* ─── Gradient slides ────────────────────────────── */
            .hero-swiper .slide-bg-gradient {
                position: absolute;
                inset: 0;
                z-index: 0;
            }

            /* ─── Image overlay: dim + bottom scrim ──────────── */
            /* Only shown when slide has an image (via .has-image class) */
            .hero-swiper .swiper-slide.has-image .slide-dim {
                position: absolute;
                inset: 0;
                z-index: 1;
                background: rgba(0, 0, 0, 0.25);
            }
            .hero-swiper .swiper-slide.has-image .slide-scrim {
                position: absolute;
                inset: 0;
                z-index: 2;
                background: linear-gradient(
                    to top,
                    rgba(0, 0, 0, 0.90) 0%,
                    rgba(0, 0, 0, 0.55) 38%,
                    rgba(0, 0, 0, 0.15) 65%,
                    transparent 100%
                );
            }

            /* ─── Gradient-only overlay: just a light bottom scrim ─ */
            .hero-swiper .swiper-slide.has-gradient .slide-dim {
                display: none; /* no dim on colored gradients */
            }
            .hero-swiper .swiper-slide.has-gradient .slide-scrim {
                position: absolute;
                inset: 0;
                z-index: 2;
                background: linear-gradient(
                    to top,
                    rgba(0, 0, 0, 0.75) 0%,
                    rgba(0, 0, 0, 0.35) 45%,
                    transparent 100%
                );
            }

            /* ─── Text content ───────────────────────────────── */
            .hero-swiper .slide-content {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                z-index: 10;
                padding: 1.25rem 1.5rem 1.5rem;
            }
            @media (min-width: 768px)  { .hero-swiper .slide-content { padding: 1.75rem 2.25rem 2rem; } }
            @media (min-width: 1024px) { .hero-swiper .slide-content { padding: 2rem 2.75rem 2.25rem; } }

            /* Force all text inside slides to be solid white */
            .hero-swiper .slide-content,
            .hero-swiper .slide-content h2,
            .hero-swiper .slide-content p,
            .hero-swiper .slide-content time,
            .hero-swiper .slide-content span,
            .hero-swiper .slide-content div {
                color: #ffffff !important;
            }
            .hero-swiper .slide-content h2:hover,
            .hero-swiper .slide-content a:hover h2 {
                color: #f0f0f0 !important;
            }

            /* ─── Pagination ─────────────────────────────────── */
            .hero-swiper .swiper-pagination {
                bottom: 14px !important;
                left: auto !important;
                right: 1.5rem !important;
                width: auto !important;
                z-index: 20;
            }
            @media (min-width: 768px) {
                .hero-swiper .swiper-pagination { right: 2.25rem !important; }
            }
            .hero-swiper .swiper-pagination-bullet {
                width: 6px; height: 6px;
                background: rgba(255,255,255,0.40);
                opacity: 1;
                border-radius: 3px;
                transition: all 0.3s;
                margin: 0 2px !important;
            }
            .hero-swiper .swiper-pagination-bullet-active {
                background: #E02020;
                width: 18px;
                border-radius: 3px;
            }

            /* ─── Nav arrows ─────────────────────────────────── */
            .hero-swiper .swiper-button-next,
            .hero-swiper .swiper-button-prev {
                color: #fff;
                background: rgba(0, 0, 0, 0.40);
                width: 36px; height: 36px;
                border-radius: 50%;
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
                border: 1px solid rgba(255,255,255,0.15);
                opacity: 0;
                transition: opacity 0.25s, background 0.2s;
                z-index: 20;
                margin-top: 0 !important;
                top: 50% !important;
                transform: translateY(-50%) !important;
            }
            .hero-swiper:hover .swiper-button-next,
            .hero-swiper:hover .swiper-button-prev { opacity: 1; }
            .hero-swiper .swiper-button-next { right: 14px !important; left: auto !important; }
            .hero-swiper .swiper-button-prev { left: 14px !important; right: auto !important; }
            .hero-swiper .swiper-button-next:after,
            .hero-swiper .swiper-button-prev:after {
                font-size: 12px;
                font-weight: 900;
            }
            .hero-swiper .swiper-button-next:hover,
            .hero-swiper .swiper-button-prev:hover {
                background: #E02020;
                border-color: #E02020;
            }
        </style>
    @endpush

    <section class="max-w-[1240px] mx-auto px-4 py-4">
        <div class="hero-swiper swiper">
            <div class="swiper-wrapper">
                @foreach($articles as $article)
                    @php
                        // Extract YouTube ID
                        $ytId = null;
                        if ($article->video_url && preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/|v\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $article->video_url, $m)) {
                            $ytId = $m[1];
                        }
                        $bgImage = $article->featured_image_url ?? ($ytId ? "https://img.youtube.com/vi/{$ytId}/maxresdefault.jpg" : null);
                        $hasImage = !empty($bgImage);

                        // Vibrant but readable dark gradient pairs for no-image slides
                        $bgPairs = [
                            ['#1a3a6e', '#0d1d3a'],   // royal blue
                            ['#8b1a1a', '#2d0808'],   // deep red
                            ['#1a4a2e', '#0a1e12'],   // forest green
                            ['#3a1a4a', '#180a20'],   // purple
                            ['#1a2a5e', '#080f28'],   // navy indigo
                            ['#4a2a0a', '#1e0f02'],   // warm amber-dark
                        ];
                        $bg = $bgPairs[$loop->index % count($bgPairs)];
                    @endphp

                    <div class="swiper-slide {{ $hasImage ? 'has-image' : 'has-gradient' }}">

                        {{-- ① Background ─────────────────────────────── --}}
                        @if($hasImage)
                            <img
                                src="{{ $bgImage }}"
                                alt="{{ $article->title_bn }}"
                                class="slide-bg-img"
                                loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                            >
                        @else
                            <div
                                class="slide-bg-gradient"
                                style="background: linear-gradient(145deg, {{ $bg[0] }} 0%, {{ $bg[1] }} 100%);"
                            ></div>
                        @endif

                        {{-- ② Overlays (conditional via CSS by .has-image / .has-gradient) ── --}}
                        <div class="slide-dim"></div>
                        <div class="slide-scrim"></div>

                        {{-- ③ Text ──────────────────────────────────────── --}}
                        <div class="slide-content">

                            {{-- Category badge --}}
                            @if($article->category)
                                <a href="{{ route('article.category', $article->category->slug) }}"
                                   class="inline-block bg-[#E02020] text-white text-[10px] font-bold uppercase tracking-widest px-2.5 py-0.5 rounded-sm mb-2.5 hover:bg-white hover:text-[#E02020] transition">
                                    {{ $article->category->name_bn }}
                                </a>
                            @endif

                            {{-- Headline --}}
                            <a href="{{ route('article.show', $article->slug) }}" class="block group">
                                <h2 class="font-serif font-black text-xl md:text-3xl lg:text-4xl text-white leading-tight mb-2 group-hover:text-gray-100 transition-colors"
                                    style="text-shadow: 0 1px 16px rgba(0,0,0,0.7); max-width: 700px;">
                                    {{ $article->title_bn }}
                                </h2>
                            </a>

                            {{-- Meta --}}
                            <div class="flex items-center gap-2 text-[11px] text-white/60">
                                @if($article->staffs->isNotEmpty())
                                    <span class="font-semibold text-white/75">{{ $article->staffs->first()->name_bn }}</span>
                                    <span>·</span>
                                @endif
                                <time datetime="{{ $article->published_at?->toIso8601String() }}">
                                    {{ $article->published_at?->diffForHumans() }}
                                </time>
                            </div>

                        </div>{{-- /.slide-content --}}
                    </div>{{-- /.swiper-slide --}}
                @endforeach
            </div>{{-- /.swiper-wrapper --}}

            <div class="swiper-pagination"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>{{-- /.hero-swiper --}}
    </section>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                new Swiper('.hero-swiper', {
                    loop: {{ $articles->count() > 1 ? 'true' : 'false' }},
                    effect: 'fade',
                    fadeEffect: { crossFade: true },
                    speed: 800,
                    autoplay: {
                        delay: 5500,
                        disableOnInteraction: false,
                        pauseOnMouseEnter: true,
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    keyboard: { enabled: true },
                });
            });
        </script>
    @endpush
@endif
