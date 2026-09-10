@extends('layouts.guest')
@section('title', 'ইমেইল যাচাই — ' . config('app.name'))
@section('content')
<div class="min-h-screen bg-[#f5f5f5] flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-md">
        <a href="/" class="block text-center mb-6">
            <span class="font-serif font-black text-3xl text-[#111]">Education Times</span>
        </a>
        <div class="bg-white border border-[#e5e5e5] p-8 text-center">
            <h1 class="font-serif font-black text-2xl text-[#111] mb-3">ইমেইল যাচাই করুন</h1>
            <p class="text-sm text-[#666] leading-relaxed">
                নিবন্ধন সম্পন্ন করতে আমরা আপনাকে একটি যাচাইকরণ লিংক ইমেইল করেছি। অনুগ্রহ করে আপনার ইমেইল চেক করুন।
            </p>
            <p class="text-xs text-[#888] mt-4">ইমেইল পাননি? <form method="POST" action="{{ route('verification.send') }}" class="inline">@csrf <a href="javascript:void(0)" onclick="this.closest('form').submit()" class="text-[#111] underline">পুনরায় পাঠান</a></form></p>
        </div>
    </div>
</div>
@endsection
