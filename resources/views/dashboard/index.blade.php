@extends('layouts.app')

@section('title', 'ড্যাশবোর্ড - ' . config('app.name'))

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-8">
            {{-- Profile Header --}}
            <div class="flex items-center justify-between mb-8 pb-6 border-b border-[#e0e0e0] dark:border-[#333]">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-[#E02020] flex items-center justify-center text-white text-xl font-bold shrink-0">
                        {{ mb_substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold dark:text-white">স্বাগতম, {{ $user->name }}</h1>
                        <p class="text-[#666] dark:text-[#999] text-sm">{{ $user->designation ?? 'সদস্য' }}{{ $user->school_name ? ', ' . $user->school_name : '' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('profile.show') }}" class="border border-[#e0e0e0] dark:border-[#444] text-sm px-4 py-2 hover:bg-gray-50 dark:hover:bg-[#2a2a2a] transition dark:text-[#e0e0e0]">
                        প্রোফাইল
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-[#E02020] hover:text-red-700 transition font-medium">লগআউট</button>
                    </form>
                </div>
            </div>

            @if(Auth::user()->is_editor)
            {{-- REPORTER DASHBOARD --}}

            {{-- Post Stats --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-8">
                <div class="bg-white dark:bg-[#1e1e1e] border border-[#e0e0e0] dark:border-[#333] p-4">
                    <p class="text-2xl font-bold dark:text-white">{{ $totalPosts }}</p>
                    <p class="text-xs text-[#666] dark:text-[#999] mt-0.5">মোট পোস্ট</p>
                </div>
                <div class="bg-white dark:bg-[#1e1e1e] border border-[#e0e0e0] dark:border-[#333] p-4">
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $publishedPosts }}</p>
                    <p class="text-xs text-[#666] dark:text-[#999] mt-0.5">প্রকাশিত</p>
                </div>
                <div class="bg-white dark:bg-[#1e1e1e] border border-[#e0e0e0] dark:border-[#333] p-4">
                    <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $pendingPosts }}</p>
                    <p class="text-xs text-[#666] dark:text-[#999] mt-0.5">পর্যালোচনায়</p>
                </div>
                <div class="bg-white dark:bg-[#1e1e1e] border border-[#e0e0e0] dark:border-[#333] p-4">
                    <p class="text-2xl font-bold text-gray-500 dark:text-gray-400">{{ $draftPosts }}</p>
                    <p class="text-xs text-[#666] dark:text-[#999] mt-0.5">খসড়া</p>
                </div>
            </div>

            {{-- Submit Post --}}
            <div class="bg-white dark:bg-[#1e1e1e] border border-[#e0e0e0] dark:border-[#333] p-6 mb-8">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="font-bold text-lg border-l-3 border-[#E02020] pl-3 dark:text-white">নতুন পোস্ট জমা দিন</h2>
                    @if(Auth::user()->is_admin)
                    <a href="{{ route('staff.articles.create') }}"
                        class="text-xs text-[#E02020] hover:text-red-700 transition font-medium">পূর্ণাঙ্গ এডিটর →</a>
                    @endif
                </div>

                @if (session('success'))
                    <div class="bg-green-50 dark:bg-green-950/20 border-l-4 border-green-600 p-4 mb-5 text-sm text-green-800 dark:text-green-400">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-50 dark:bg-red-950/20 border-l-4 border-[#E02020] p-4 mb-5">
                        <ul class="text-sm text-[#E02020] dark:text-red-400 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('dashboard.post.store') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-[#666] dark:text-[#999] mb-1">শিরোনাম *</label>
                        <input type="text" name="title_bn" value="{{ old('title_bn') }}" required
                            class="w-full border border-[#e0e0e0] dark:border-[#444] dark:bg-[#2a2a2a] dark:text-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d] dark:focus:border-[#888] transition">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-[#666] dark:text-[#999] mb-1">বিভাগ *</label>
                            <select name="category_id" required
                                class="w-full border border-[#e0e0e0] dark:border-[#444] dark:bg-[#2a2a2a] dark:text-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d] dark:focus:border-[#888] transition">
                                <option value="">বিভাগ নির্বাচন করুন</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name_bn }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#666] dark:text-[#999] mb-1">ট্যাগ (কমা দিয়ে)</label>
                            <input type="text" name="tags" value="{{ old('tags') }}" placeholder="শিক্ষা, নীতি, সরকার"
                                class="w-full border border-[#e0e0e0] dark:border-[#444] dark:bg-[#2a2a2a] dark:text-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d] dark:focus:border-[#888] transition">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#666] dark:text-[#999] mb-1">সারসংক্ষেপ</label>
                        <textarea name="excerpt_bn" rows="2"
                            class="w-full border border-[#e0e0e0] dark:border-[#444] dark:bg-[#2a2a2a] dark:text-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d] dark:focus:border-[#888] transition">{{ old('excerpt_bn') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#666] dark:text-[#999] mb-1">বিবরণ *</label>
                        <textarea name="body_bn" rows="8" required
                            class="w-full border border-[#e0e0e0] dark:border-[#444] dark:bg-[#2a2a2a] dark:text-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d] dark:focus:border-[#888] transition leading-relaxed">{{ old('body_bn') }}</textarea>
                        <p class="text-xs text-[#999] dark:text-[#777] mt-1">HTML ট্যাগ ব্যবহার করা যাবে। পূর্ণাঙ্গ এডিটর ব্যবহার করতে চাইলে উপরের লিংকে ক্লিক করুন।</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#666] dark:text-[#999] mb-1">ফিচারড ইমেজ</label>
                        <div class="flex gap-2">
                            <input type="file" name="featured_image" accept="image/*"
                                class="flex-1 border border-[#e0e0e0] dark:border-[#444] dark:bg-[#2a2a2a] dark:text-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d] dark:focus:border-[#888] transition file:mr-3 file:border-0 file:bg-[#f5f5f5] dark:file:bg-[#333] file:px-3 file:py-1.5 file:text-xs file:font-medium">
                        </div>
                        <p class="text-xs text-[#999] dark:text-[#777] mt-1">JPG, PNG বা WEBP ফাইল নির্বাচন করুন</p>
                    </div>
                    <div class="flex justify-end pt-2">
                        <button type="submit"
                            class="bg-[#E02020] text-white px-6 py-2.5 text-sm font-medium hover:bg-red-700 transition">
                            পর্যালোচনার জন্য জমা দিন
                        </button>
                    </div>
                </form>
            </div>

            {{-- My Posts --}}
            <div class="bg-white dark:bg-[#1e1e1e] border border-[#e0e0e0] dark:border-[#333] overflow-hidden">
                <div class="flex items-center justify-between p-5 pb-0">
                    <h2 class="font-bold text-lg border-l-3 border-[#E02020] pl-3 dark:text-white">আমার পোস্ট</h2>
                    @if($totalPosts > 10)
                    <a href="{{ route('staff.articles.index') }}"
                        class="text-xs text-[#E02020] hover:text-red-700 transition font-medium">সব দেখুন →</a>
                    @endif
                </div>

                @if($articles->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-sm mt-4">
                        <thead class="bg-[#f5f5f5] dark:bg-[#2a2a2a] border-y border-[#e0e0e0] dark:border-[#333]">
                            <tr>
                                <th class="text-left p-3 font-semibold dark:text-[#e0e0e0]">শিরোনাম</th>
                                <th class="text-left p-3 font-semibold dark:text-[#e0e0e0] hidden md:table-cell">বিভাগ</th>
                                <th class="text-left p-3 font-semibold dark:text-[#e0e0e0]">স্ট্যাটাস</th>
                                <th class="text-left p-3 font-semibold dark:text-[#e0e0e0] hidden sm:table-cell">তারিখ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e0e0e0] dark:divide-[#333]">
                            @foreach($articles as $article)
                            <tr class="hover:bg-[#fafafa] dark:hover:bg-[#2a2a2a]">
                                <td class="p-3">
                                    <a href="{{ route('article.show', $article->slug) }}"
                                        class="text-sm font-medium dark:text-white hover:text-[#E02020] dark:hover:text-[#ff6b6b] transition line-clamp-1 {{ $article->status === 'draft' ? 'text-[#999] dark:text-[#777]' : '' }}">
                                        {{ Str::limit($article->title_bn, 50) }}
                                    </a>
                                </td>
                                <td class="p-3 text-[#666] dark:text-[#999] hidden md:table-cell text-xs">{{ $article->category?->name_bn ?? '-' }}</td>
                                <td class="p-3">
                                    @php
                                        $statusClasses = [
                                            'published' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
                                            'submitted' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400',
                                            'scheduled' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
                                            'draft' => 'bg-gray-100 dark:bg-gray-800 text-[#666] dark:text-[#999]',
                                            'archived' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400',
                                        ];
                                        $statusLabels = [
                                            'published' => 'প্রকাশিত', 'submitted' => 'পর্যালোচনায়',
                                            'scheduled' => 'নির্ধারিত', 'draft' => 'খসড়া', 'archived' => 'আর্কাইভ',
                                        ];
                                    @endphp
                                    <span class="text-xs px-2 py-0.5 {{ $statusClasses[$article->status] ?? 'bg-gray-100 dark:bg-gray-800 text-[#666] dark:text-[#999]' }}">
                                        {{ $statusLabels[$article->status] ?? $article->status }}
                                    </span>
                                </td>
                                <td class="p-3 text-[#999] dark:text-[#777] text-xs hidden sm:table-cell">
                                    {{ $article->created_at->format('d/m/Y') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-[#e0e0e0] dark:border-[#333]">
                    {{ $articles->links() }}
                </div>
                @else
                <div class="p-8 text-center">
                    <svg class="h-10 w-10 mx-auto text-[#ccc] dark:text-[#555] mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    <p class="text-sm text-[#999] dark:text-[#777]">আপনি এখনো কোনো পোস্ট জমা দেননি।</p>
                </div>
                @endif
            </div>

            @else
            {{-- READER DASHBOARD --}}

            {{-- Reader Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-white dark:bg-[#1e1e1e] border border-[#e0e0e0] dark:border-[#333] p-5">
                    <p class="text-3xl font-bold dark:text-white">{{ $savedArticles->count() }}</p>
                    <p class="text-sm text-[#666] dark:text-[#999] mt-1">সংরক্ষিত সংবাদ</p>
                </div>
                <div class="bg-white dark:bg-[#1e1e1e] border border-[#e0e0e0] dark:border-[#333] p-5">
                    <p class="text-3xl font-bold dark:text-white">{{ $comments->count() }}</p>
                    <p class="text-sm text-[#666] dark:text-[#999] mt-1">মন্তব্য</p>
                </div>
                <div class="bg-white dark:bg-[#1e1e1e] border border-[#e0e0e0] dark:border-[#333] p-5">
                    <p class="text-3xl font-bold dark:text-white">{{ (int) \Carbon\Carbon::parse($user->created_at)->diffInDays(now()) }}</p>
                    <p class="text-sm text-[#666] dark:text-[#999] mt-1">সক্রিয় দিন</p>
                </div>
            </div>
            @endif

            {{-- Saved + Comments Grid (ALL users) --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @if($savedArticles->isNotEmpty())
                <div class="bg-white dark:bg-[#1e1e1e] border border-[#e0e0e0] dark:border-[#333] p-6">
                    <h2 class="font-bold text-lg mb-4 border-l-3 border-[#E02020] pl-3 dark:text-white">সংরক্ষিত সংবাদ</h2>
                    <div class="space-y-3">
                        @foreach($savedArticles as $saved)
                        @if($saved->article)
                        <div class="pb-3 border-b border-[#e0e0e0] dark:border-[#333] last:border-0 last:pb-0">
                            <a href="{{ route('article.show', $saved->article->slug) }}"
                                class="text-sm font-semibold hover:text-[#E02020] dark:hover:text-[#ff6b6b] transition-colors dark:text-[#e0e0e0]">{{ $saved->article->title_bn }}</a>
                            <p class="text-xs text-[#999] dark:text-[#777] mt-0.5">{{ $saved->article->published_at?->diffForHumans() }}</p>
                        </div>
                        @endif
                        @endforeach
                    </div>
                    @if($savedArticles->count() >= 5)
                    <a href="{{ route('profile.show') }}" class="block text-center text-xs text-[#E02020] hover:text-red-700 transition mt-4 font-medium">সব দেখুন</a>
                    @endif
                </div>
                @endif

                @if($comments->isNotEmpty())
                <div class="bg-white dark:bg-[#1e1e1e] border border-[#e0e0e0] dark:border-[#333] p-6">
                    <h2 class="font-bold text-lg mb-4 border-l-3 border-[#E02020] pl-3 dark:text-white">আমার মন্তব্য</h2>
                    <div class="space-y-3">
                        @foreach($comments as $comment)
                        <div class="pb-3 border-b border-[#e0e0e0] dark:border-[#333] last:border-0 last:pb-0">
                            <p class="text-sm dark:text-[#e0e0e0]">{{ Str::limit($comment->body, 120) }}</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs text-[#999] dark:text-[#777]">{{ $comment->created_at->diffForHumans() }}</span>
                                <span class="text-[#ccc] dark:text-[#555]">|</span>
                                @if($comment->article)
                                <a href="{{ route('article.show', $comment->article->slug) }}" class="text-xs text-[#E02020] hover:text-red-700 transition">{{ $comment->article->title_bn }}</a>
                                @else
                                <span class="text-xs text-[#999] dark:text-[#777]">(অপসারিত)</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @if($comments->count() >= 5)
                    <a href="{{ route('profile.show') }}" class="block text-center text-xs text-[#E02020] hover:text-red-700 transition mt-4 font-medium">সব দেখুন</a>
                    @endif
                </div>
                @endif

                @if($savedArticles->isEmpty() && $comments->isEmpty())
                <div class="lg:col-span-2 bg-white dark:bg-[#1e1e1e] border border-[#e0e0e0] dark:border-[#333] p-8 text-center">
                    <svg class="h-10 w-10 mx-auto text-[#ccc] dark:text-[#555] mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    <p class="text-sm text-[#999] dark:text-[#777]">এখনো কোনো কার্যকলাপ নেই। সংবাদ পড়া শুরু করুন এবং মন্তব্য করুন!</p>
                    <a href="/" class="inline-block mt-4 bg-[#E02020] text-white text-sm px-5 py-2 hover:bg-red-700 transition">সংবাদ পড়ুন</a>
                </div>
                @endif
            </div>
        </div>

        <aside class="lg:col-span-4 space-y-6">
            {{-- Sidebar Stats --}}
            <div class="bg-white dark:bg-[#1e1e1e] border border-[#e0e0e0] dark:border-[#333] p-5 space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-[#666] dark:text-[#999]">ইমেইল</span>
                    <span class="text-sm dark:text-[#e0e0e0] text-right truncate max-w-[180px]">{{ $user->email }}</span>
                </div>
                @if(Auth::user()->is_editor)
                <div class="flex items-center justify-between border-t border-[#e0e0e0] dark:border-[#333] pt-4">
                    <span class="text-sm text-[#666] dark:text-[#999]">মোট পোস্ট</span>
                    <span class="font-bold dark:text-white">{{ $totalPosts }}</span>
                </div>
                <div class="flex items-center justify-between border-t border-[#e0e0e0] dark:border-[#333] pt-4">
                    <span class="text-sm text-[#666] dark:text-[#999]">প্রকাশিত</span>
                    <span class="font-bold text-green-600 dark:text-green-400">{{ $publishedPosts }}</span>
                </div>
                <div class="flex items-center justify-between border-t border-[#e0e0e0] dark:border-[#333] pt-4">
                    <span class="text-sm text-[#666] dark:text-[#999]">পর্যালোচনায়</span>
                    <span class="font-bold text-yellow-600 dark:text-yellow-400">{{ $pendingPosts }}</span>
                </div>
                @else
                <div class="flex items-center justify-between border-t border-[#e0e0e0] dark:border-[#333] pt-4">
                    <span class="text-sm text-[#666] dark:text-[#999]">ফোন</span>
                    <span class="text-sm dark:text-[#e0e0e0]">{{ $user->phone ?? '-' }}</span>
                </div>
                <div class="flex items-center justify-between border-t border-[#e0e0e0] dark:border-[#333] pt-4">
                    <span class="text-sm text-[#666] dark:text-[#999]">জেলা</span>
                    <span class="text-sm dark:text-[#e0e0e0]">{{ $user->district?->name_bn ?? '-' }}</span>
                </div>
                @endif
                <div class="flex items-center justify-between border-t border-[#e0e0e0] dark:border-[#333] pt-4">
                    <span class="text-sm text-[#666] dark:text-[#999]">সক্রিয় দিন</span>
                    <span class="font-bold dark:text-white">{{ (int) \Carbon\Carbon::parse($user->created_at)->diffInDays(now()) }}</span>
                </div>
                <div class="border-t border-[#e0e0e0] dark:border-[#333] pt-4">
                    <a href="{{ route('profile.edit') }}" class="block w-full text-center border border-[#e0e0e0] dark:border-[#444] text-sm px-4 py-2 hover:bg-gray-50 dark:hover:bg-[#2a2a2a] transition dark:text-[#e0e0e0]">
                        প্রোফাইল সম্পাদনা
                    </a>
                </div>
            </div>

            @include('partials.ads.sidebar')
        </aside>
    </div>
</div>
@endsection
