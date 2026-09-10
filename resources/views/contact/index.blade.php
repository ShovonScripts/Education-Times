@extends('layouts.app')
@section('title', 'যোগাযোগ — ' . config('app.name'))
@section('content')
<div class="max-w-[1240px] mx-auto px-4 py-6">
    <header class="border-b-2 border-[#111] pb-3 mb-6">
        <h1 class="font-serif font-black text-3xl text-[#111]">যোগাযোগ</h1>
        <p class="text-sm text-[#666] mt-1">আপনার মতামত, পরামর্শ বা কোনো প্রশ্ন থাকলে নিচের ফর্মটি ব্যবহার করে আমাদের জানাতে পারেন।</p>
    </header>

    @if(session('success'))
    <div class="mb-6 border-l-4 border-[#111] bg-[#f5f5f5] px-4 py-3 text-sm text-[#111]">
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
        <div class="md:col-span-8">
            <form method="POST" action="{{ route('contact.store') }}" class="bg-white border border-[#e5e5e5] p-6 md:p-8 space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-[#111] mb-1.5">আপনার নাম *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full border border-[#e5e5e5] focus:border-[#111] focus:outline-none focus:ring-1 focus:ring-[#111] bg-white px-3 py-2.5 text-sm">
                        @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-[#111] mb-1.5">ইমেইল *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full border border-[#e5e5e5] focus:border-[#111] focus:outline-none focus:ring-1 focus:ring-[#111] bg-white px-3 py-2.5 text-sm">
                        @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-[#111] mb-1.5">ফোন (ঐচ্ছিক)</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="w-full border border-[#e5e5e5] focus:border-[#111] focus:outline-none focus:ring-1 focus:ring-[#111] bg-white px-3 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-[#111] mb-1.5">বিষয় *</label>
                        <input type="text" name="subject" value="{{ old('subject') }}" required class="w-full border border-[#e5e5e5] focus:border-[#111] focus:outline-none focus:ring-1 focus:ring-[#111] bg-white px-3 py-2.5 text-sm">
                        @error('subject') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-[#111] mb-1.5">বার্তা *</label>
                    <textarea name="message" rows="6" required class="w-full border border-[#e5e5e5] focus:border-[#111] focus:outline-none focus:ring-1 focus:ring-[#111] bg-white px-3 py-2.5 text-sm resize-y">{{ old('message') }}</textarea>
                    @error('message') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="bg-[#111] hover:bg-black text-white text-sm font-semibold uppercase tracking-wider px-6 py-3 transition">বার্তা পাঠান</button>
            </form>
        </div>

        <aside class="md:col-span-4 space-y-6">
            <div class="border border-[#e5e5e5] p-6">
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#111] mb-3 pb-2 border-b border-[#111]">ইমেইল</h3>
                <p class="text-sm"><a href="mailto:info@educationtimes.com" class="text-[#444] hover:text-[#111] hover:underline">info@educationtimes.com</a></p>
            </div>
            <div class="border border-[#e5e5e5] p-6">
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#111] mb-3 pb-2 border-b border-[#111]">ঠিকানা</h3>
                <p class="text-sm text-[#444] leading-relaxed">ঢাকা, বাংলাদেশ</p>
            </div>
            <div class="border border-[#e5e5e5] p-6">
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#111] mb-3 pb-2 border-b border-[#111]">সোশ্যাল</h3>
                <div class="flex items-center gap-2">
                    @if($fb = \App\Models\Setting::get('social_facebook'))
                    <a href="{{ $fb }}" class="w-9 h-9 border border-[#111] text-[#111] hover:bg-[#111] hover:text-white flex items-center justify-center transition" target="_blank" rel="noopener">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    @endif
                    @if($yt = \App\Models\Setting::get('social_youtube'))
                    <a href="{{ $yt }}" class="w-9 h-9 border border-[#111] text-[#111] hover:bg-[#111] hover:text-white flex items-center justify-center transition" target="_blank" rel="noopener">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                    @endif
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection
