@extends('layouts.app')

@section('title', 'প্রোফাইল — ' . config('app.name'))

@section('content')
<div class="max-w-[1240px] mx-auto px-4 py-6">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-8">
            <div class="flex items-center justify-between mb-8 pb-6 border-b-2 border-[#111]">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-[#111] text-white flex items-center justify-center text-2xl font-serif font-bold shrink-0">
                        {{ mb_substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <h1 class="font-serif font-black text-2xl text-[#111]">{{ $user->name }}</h1>
                        <p class="text-[#666] text-sm">{{ $user->designation ?? 'সদস্য' }}{{ $user->school_name ? ', ' . $user->school_name : '' }}</p>
                    </div>
                </div>
                <a href="{{ route('profile.edit') }}" class="border border-[#111] text-[#111] hover:bg-[#111] hover:text-white text-sm font-semibold uppercase tracking-wider px-4 py-2 transition">প্রোফাইল সম্পাদনা</a>
            </div>

            <div x-data="{ tab: 'profile' }">
                <div class="flex border-b-2 border-[#111] mb-6">
                    <button @click="tab = 'profile'" :class="{ 'border-b-2 border-[#111] text-[#111] font-semibold bg-[#111] text-white': tab === 'profile' }" class="px-5 py-3 text-sm text-[#666] hover:text-[#111] transition">প্রোফাইল</button>
                    <button @click="tab = 'comments'" :class="{ 'border-b-2 border-[#111] text-[#111] font-semibold bg-[#111] text-white': tab === 'comments' }" class="px-5 py-3 text-sm text-[#666] hover:text-[#111] transition">মন্তব্য</button>
                    <button @click="tab = 'liked'" :class="{ 'border-b-2 border-[#111] text-[#111] font-semibold bg-[#111] text-white': tab === 'liked' }" class="px-5 py-3 text-sm text-[#666] hover:text-[#111] transition">পছন্দ করা</button>
                </div>

                <div x-show="tab === 'profile'" x-cloak>
                    <div class="bg-white border border-[#e5e5e5] p-6">
                        <h2 class="font-serif font-black text-lg mb-6 border-l-4 border-[#111] pl-3 text-[#111]">ব্যক্তিগত তথ্য</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-xs font-bold text-[#666] uppercase tracking-wider mb-1">ইমেইল</p>
                                <p class="text-sm text-[#111]">{{ $user->email }}</p>
                            </div>
                            @if($user->phone)
                            <div>
                                <p class="text-xs font-bold text-[#666] uppercase tracking-wider mb-1">ফোন</p>
                                <p class="text-sm text-[#111]">{{ $user->phone }}</p>
                            </div>
                            @endif
                            @if($user->school_name)
                            <div>
                                <p class="text-xs font-bold text-[#666] uppercase tracking-wider mb-1">বিদ্যালয়</p>
                                <p class="text-sm text-[#111]">{{ $user->school_name }}</p>
                            </div>
                            @endif
                            <div>
                                <p class="text-xs font-bold text-[#666] uppercase tracking-wider mb-1">যোগদানের তারিখ</p>
                                <p class="text-sm text-[#111]">{{ $user->created_at->locale('bn')->translatedFormat('j F Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="tab === 'comments'" x-cloak>
                    <div class="bg-white border border-[#e5e5e5] p-6">
                        <h2 class="font-serif font-black text-lg mb-4 border-l-4 border-[#111] pl-3 text-[#111]">আমার মন্তব্য</h2>
                        @if(isset($comments) && $comments->isNotEmpty())
                        <ul class="space-y-4">
                            @foreach($comments as $c)
                            <li class="pb-4 border-b border-[#e5e5e5] last:border-0">
                                <p class="text-sm text-[#222]">{{ $c->body }}</p>
                                <p class="text-xs text-[#666] mt-1">
                                    <a href="{{ route('article.show', $c->article->slug ?? '#') }}" class="hover:underline">{{ $c->article->title_bn ?? 'নিবন্ধ' }}</a> · {{ $c->created_at->diffForHumans() }}
                                </p>
                            </li>
                            @endforeach
                        </ul>
                        @else
                        <p class="text-sm text-[#666]">আপনার কোনো মন্তব্য নেই।</p>
                        @endif
                    </div>
                </div>

                <div x-show="tab === 'liked'" x-cloak>
                    <div class="bg-white border border-[#e5e5e5] p-6">
                        <h2 class="font-serif font-black text-lg mb-4 border-l-4 border-[#111] pl-3 text-[#111]">পছন্দ করা সংবাদ</h2>
                        @if(isset($likedArticles) && $likedArticles->isNotEmpty())
                        <ul class="space-y-3">
                            @foreach($likedArticles as $l)
                            <li class="pb-3 border-b border-[#e5e5e5] last:border-0">
                                <a href="{{ route('article.show', $l->slug ?? '#') }}" class="text-sm text-[#111] hover:underline font-semibold">{{ $l->title_bn ?? 'নিবন্ধ' }}</a>
                            </li>
                            @endforeach
                        </ul>
                        @else
                        <p class="text-sm text-[#666]">আপনি কোনো সংবাদ পছন্দ করেননি।</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <aside class="lg:col-span-4">@include('partials.ads.sidebar')</aside>
    </div>
</div>
@endsection
