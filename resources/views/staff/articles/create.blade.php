@extends('layouts.staff')
@section('title', 'নতুন আর্টিকেল')
@section('content')
<div class="mb-6">
    <a href="{{ route('staff.articles.index') }}" class="inline-flex items-center gap-1 text-xs text-[#999] dark:text-[#777] hover:text-[#0d0d0d] dark:hover:text-white transition">
        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        ফিরে যান
    </a>
</div>
<h1 class="font-serif text-2xl font-bold mb-6 dark:text-white">নতুন আর্টিকেল</h1>
<div class="bg-white dark:bg-[#1e1e1e] border border-[#e0e0e0] dark:border-[#333] p-6 md:p-8">
    <form method="POST" action="{{ route('staff.articles.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-[#666] dark:text-[#999] mb-1">শিরোনাম (বাংলা) *</label>
            <input type="text" name="title_bn" value="{{ old('title_bn') }}" required
                class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]">
        </div>
        <div>
            <label class="block text-sm font-medium text-[#666] dark:text-[#999] mb-1">বিভাগ *</label>
            <select name="category_id" required
                class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-[#0d0d0d]">
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name_bn }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-[#666] dark:text-[#999] mb-1">সারসংক্ষেপ</label>
            <textarea name="excerpt_bn" rows="2"
                class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]">{{ old('excerpt_bn') }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-[#666] dark:text-[#999] mb-1">বডি *</label>
            <textarea name="body_bn" rows="15" required
                class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d] font-mono leading-relaxed">{{ old('body_bn') }}</textarea>
            <p class="text-xs text-[#999] dark:text-[#777] mt-1">HTML ট্যাগ ব্যবহার করা যাবে</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-[#666] dark:text-[#999] mb-1">ফিচার্ড ইমেজ (JPEG/PNG/WebP)</label>
            <input type="file" name="featured_image" accept="image/*"
                class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d] file:mr-3 file:border-0 file:bg-[#f5f5f5] file:px-3 file:py-1.5 file:text-xs file:font-medium">
            @if(isset($article) && $article->featured_image_url)
            <div class="mt-2 flex items-center gap-3">
                <img src="{{ $article->featured_image_url }}" alt="" class="w-24 h-16 object-cover border border-[#e0e0e0]">
                <label class="flex items-center gap-2 text-xs text-[#999] cursor-pointer">
                    <input type="checkbox" name="remove_featured_image" value="1" class="accent-[#E02020]"> বর্তমান ছবি সরান
                </label>
            </div>
            @endif
            <p class="text-xs text-[#999] mt-1">নতুন ছবি দিলে আগেরটি স্বয়ংক্রিয়ভাবে মুছে যাবে।</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-[#666] dark:text-[#999] mb-1">ইউটিউব ভিডিও লিংক</label>
            <input type="text" name="video_url" value="{{ old('video_url') }}" placeholder="https://youtube.com/watch?v=..."
                class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]">
        </div>
        <div>
            <label class="block text-sm font-medium text-[#666] dark:text-[#999] mb-1">ট্যাগ (কমা দিয়ে আলাদা করুন)</label>
            <input type="text" name="tags" value="{{ old('tags') }}"
                class="w-full border border-[#e0e0e0] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d]">
        </div>
        <div class="flex justify-end gap-3 pt-4 border-t border-[#e0e0e0] dark:border-[#333]">
            <button type="submit"
                class="border border-[#0d0d0d] dark:border-[#888] text-[#0d0d0d] dark:text-[#e0e0e0] px-6 py-2.5 text-sm font-medium hover:bg-[#f5f5f5] dark:hover:bg-[#2a2a2a] transition">
                খসড়া হিসেবে সংরক্ষণ
            </button>
        </div>
    </form>
</div>
@endsection
