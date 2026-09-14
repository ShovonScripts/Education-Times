@extends('layouts.guest')
@section('title', 'রেজিস্টার — ' . config('app.name'))
@section('content')
<div class="w-full max-w-sm">
        <div class="text-center mb-8">
            @if($siteLogo = \App\Models\Setting::get('site_logo'))
                <img src="{{ Storage::url($siteLogo) }}" alt="{{ \App\Models\Setting::get('site_name_bn', config('app.name')) }}" class="h-14 w-auto mx-auto mb-3">
            @else
                <span class="font-serif font-black text-3xl text-[#111]">{{ \App\Models\Setting::get('site_name_bn', 'Education Times') }}</span>
            @endif
            <p class="text-[10px] tracking-[0.3em] text-[#666] uppercase">এডুকেশন টাইমস · বাংলাদেশ</p>
        </div>

        <div class="bg-white border border-[#e5e5e5] p-8">
            <h1 class="font-serif font-black text-2xl text-[#111] mb-1">রেজিস্টার</h1>
            <p class="text-sm text-[#666] mb-6">নতুন অ্যাকাউন্ট তৈরি করুন</p>

            @if($errors->any())
            <div class="mb-4 border-l-4 border-[#E02020] bg-red-50 px-4 py-3 text-sm text-[#E02020]">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-[#111] mb-1.5">নাম</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus class="w-full border border-[#e5e5e5] focus:border-[#111] focus:outline-none focus:ring-1 focus:ring-[#111] px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-[#111] mb-1.5">ইমেইল</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full border border-[#e5e5e5] focus:border-[#111] focus:outline-none focus:ring-1 focus:ring-[#111] px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-[#111] mb-1.5">পাসওয়ার্ড</label>
                    <input type="password" name="password" required class="w-full border border-[#e5e5e5] focus:border-[#111] focus:outline-none focus:ring-1 focus:ring-[#111] px-3 py-2.5 text-sm">
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

        <p class="text-center text-xs text-[#999] mt-6">
            <a href="/" class="hover:text-[#111] transition">← হোমপেজে ফিরুন</a>
        </p>
@endsection
