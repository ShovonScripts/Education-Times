@extends('layouts.admin')
@section('title', 'New Article')
@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.articles.index') }}" class="text-xs text-[#999] hover:text-[#0d0d0d] transition"><span class="flex items-center gap-1"><svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg> Go Back</span></a>
    </div>
    <h1 class="font-serif text-2xl font-bold mb-6">New Article</h1>
    <div class="bg-white dark:bg-[#1a1a1a] border border-[#e0e0e0] dark:border-[#333] p-6 md:p-8">
        <form method="POST" action="{{ route('admin.articles.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-[#666] dark:text-[#aaa] mb-1">Title (বাংলা) *</label>
                <input type="text" name="title_bn" value="{{ old('title_bn') }}" required
                    class="w-full border border-[#e0e0e0] dark:border-[#444] dark:bg-[#222] dark:text-[#eee] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d] dark:focus:border-white">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-[#666] dark:text-[#aaa] mb-1">Categories *</label>
                    <select name="category_id" required
                        class="w-full border border-[#e0e0e0] dark:border-[#444] dark:bg-[#222] dark:text-[#eee] px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-[#0d0d0d] dark:focus:border-white">
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name_bn }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#666] dark:text-[#aaa] mb-1">প্রতিবেদক (একাধিক নির্বাচন করতে ctrl+ক্লিক)</label>
                    <select name="staff_ids[]" multiple
                        class="w-full border border-[#e0e0e0] dark:border-[#444] dark:bg-[#222] dark:text-[#eee] px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-[#0d0d0d] dark:focus:border-white min-h-[100px]">
                        @foreach($staff as $s)
                        <option value="{{ $s->id }}" @selected(in_array($s->id, old('staff_ids', [])))>{{ $s->name_bn }} — {{ $s->designation_bn }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-[#666] dark:text-[#aaa] mb-1">জেলা</label>
                    <select name="district_id"
                        class="w-full border border-[#e0e0e0] dark:border-[#444] dark:bg-[#222] dark:text-[#eee] px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-[#0d0d0d] dark:focus:border-white">
                        <option value="">—</option>
                        @foreach($districts as $d)
                        <option value="{{ $d->id }}">{{ $d->name_bn }}</option>
                        @endforeach
                    </select>
                </div>
                <div></div>
            </div>
            <div>
                <label class="block text-sm font-medium text-[#666] dark:text-[#aaa] mb-1">Excerpt</label>
                <textarea name="excerpt_bn" rows="2"
                    class="w-full border border-[#e0e0e0] dark:border-[#444] dark:bg-[#222] dark:text-[#eee] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d] dark:focus:border-white">{{ old('excerpt_bn') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-[#666] dark:text-[#aaa] mb-1">বডি *</label>
                <textarea name="body_bn" data-editor required
                    class="w-full border border-[#e0e0e0] dark:border-[#444] px-4 py-2.5 text-sm">{{ old('body_bn') }}</textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-[#666] dark:text-[#aaa] mb-1">ফিচার্ড ইমেজ (JPEG/PNG/WebP)</label>
                    <input type="file" name="featured_image" accept="image/*"
                        class="w-full border border-[#e0e0e0] dark:border-[#444] dark:bg-[#222] dark:text-[#eee] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d] dark:focus:border-white file:mr-3 file:border-0 file:bg-[#f5f5f5] dark:file:bg-[#333] file:px-3 file:py-1.5 file:text-xs file:font-medium">
                    @if(isset($article) && $article->featured_image_url)
                    <div class="mt-2 flex items-center gap-3">
                        <img src="{{ $article->featured_image_url }}" alt="" class="w-24 h-16 object-cover border border-[#e0e0e0] dark:border-[#444]">
                        <label class="flex items-center gap-2 text-xs text-[#999] cursor-pointer">
                            <input type="checkbox" name="remove_featured_image" value="1" class="accent-[#E02020]"> বর্তমান ছবি সরান
                        </label>
                    </div>
                    @endif
                    <p class="text-xs text-[#999] mt-1">নতুন ছবি দিলে আগেরটি স্বয়ংক্রিয়ভাবে মুছে যাবে।</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#666] dark:text-[#aaa] mb-1">ইউটিউব Video Link</label>
                    <input type="text" name="video_url" value="{{ old('video_url') }}" placeholder="https://youtube.com/watch?v=..."
                        class="w-full border border-[#e0e0e0] dark:border-[#444] dark:bg-[#222] dark:text-[#eee] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d] dark:focus:border-white">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-[#666] dark:text-[#aaa] mb-1">Scheduled প্রকাশের সময়</label>
                <input type="datetime-local" name="published_at" value="{{ old('published_at') }}"
                    class="w-full border border-[#e0e0e0] dark:border-[#444] dark:bg-[#222] dark:text-[#eee] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d] dark:focus:border-white">
                <p class="text-xs text-[#999] mt-1">শুধুমাত্র Scheduled প্রকাশের জন্য পূরণ করুন</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-[#666] dark:text-[#aaa] mb-1">Tags (কমা দিয়ে আলাদা করুন)</label>
                <input type="text" name="tags" value="{{ old('tags') }}"
                    class="w-full border border-[#e0e0e0] dark:border-[#444] dark:bg-[#222] dark:text-[#eee] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d] dark:focus:border-white">
            </div>
            <div class="flex items-center gap-6 pt-2">
                <label class="flex items-center gap-2 text-sm text-[#666] dark:text-[#aaa]">
                    <input type="checkbox" name="is_breaking" value="1" class="accent-[#E02020]">
                    Breaking নিউজ
                </label>
                <label class="flex items-center gap-2 text-sm text-[#666] dark:text-[#aaa]">
                    <input type="checkbox" name="is_featured" value="1" class="accent-[#0d0d0d]">
                    Featured
                </label>
                <label class="flex items-center gap-2 text-sm text-[#666] dark:text-[#aaa]">
                    <input type="checkbox" name="is_editor_pick" value="1" class="accent-[#0d0d0d]">
                    এডিটরস পিক
                </label>
            </div>
            <div class="flex gap-3 pt-4 border-t border-[#e0e0e0] dark:border-[#333]">
                <button type="submit" name="status" value="draft"
                    class="border border-[#0d0d0d] dark:border-white text-[#0d0d0d] dark:text-white px-6 py-2.5 text-sm font-medium hover:bg-[#f5f5f5] dark:hover:bg-[#333] transition">
                    Draft হিসেবে সংরক্ষণ
                </button>
                <button type="submit" name="status" value="published"
                    class="bg-[#E02020] text-white px-6 py-2.5 text-sm font-medium hover:bg-red-700 transition">
                    প্রকাশ করুন
                </button>
                <button type="submit" name="status" value="scheduled"
                    class="bg-blue-600 text-white px-6 py-2.5 text-sm font-medium hover:bg-blue-700 transition">
                    Scheduled করুন
                </button>
            </div>
        </form>
    </div>
    <div class="mt-6 bg-white dark:bg-[#1a1a1a] border border-[#e0e0e0] dark:border-[#333] p-6 text-center">
        <p class="text-sm text-[#999]">SEO Analysis Posts সেভ করার পর পাওয়া যাবে</p>
        <p class="text-xs text-[#999] mt-1">এডিট Pagesে গিয়ে Details SEO বিশ্লেষণ দেখুন</p>
    </div>


@endsection

@push('editor')
<x-head.editor-config/>
@endpush
