@extends('layouts.guest')
@section('title', 'রেজিস্টার — ' . config('app.name'))
@section('content')
<div class="min-h-screen bg-[#f5f5f5] flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-md">
        <a href="/" class="block text-center mb-6">
            <span class="font-serif font-black text-3xl text-[#111]">Education Times</span>
            <span class="block text-[10px] tracking-[0.3em] text-[#666] uppercase mt-1">এডুকেশন টাইমস</span>
        </a>

        <div class="bg-white border border-[#e5e5e5] p-8">
            <h1 class="font-serif font-black text-2xl text-[#111] mb-1">রেজিস্টার</h1>
            <p class="text-sm text-[#666] mb-6">নতুন অ্যাকাউন্ট তৈরি করুন</p>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-[#111] mb-1.5">নাম</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus class="w-full border border-[#e5e5e5] focus:border-[#111] focus:outline-none focus:ring-1 focus:ring-[#111] px-3 py-2.5 text-sm">
                    @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-[#111] mb-1.5">ইমেইল</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full border border-[#e5e5e5] focus:border-[#111] focus:outline-none focus:ring-1 focus:ring-[#111] px-3 py-2.5 text-sm">
                    @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-[#111] mb-1.5">পাসওয়ার্ড</label>
                    <input type="password" name="password" required class="w-full border border-[#e5e5e5] focus:border-[#111] focus:outline-none focus:ring-1 focus:ring-[#111] px-3 py-2.5 text-sm">
                    @error('password') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-[#111] mb-1.5">পাসওয়ার্ড নিশ্চিত করুন</label>
                    <input type="password" name="password_confirmation" required class="w-full border border-[#e5e5e5] focus:border-[#111] focus:outline-none focus:ring-1 focus:ring-[#111] px-3 py-2.5 text-sm">
                </div>
                <button type="submit" class="w-full bg-[#111] hover:bg-black text-white text-sm font-semibold uppercase tracking-wider py-3 transition">রেজিস্টার</button>
            </form>

            <p class="text-sm text-center text-[#666] mt-5 pt-5 border-t border-[#e5e5e5]">
                ইতিমধ্যে অ্যাকাউন্ট আছে? <a href="{{ route('login') }}" class="text-[#111] font-semibold hover:underline">লগইন করুন</a>
            </p>
        </div>
    </div>
</div>
@endsection
