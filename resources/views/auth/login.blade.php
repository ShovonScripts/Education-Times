@extends('layouts.guest')
@section('title', 'লগইন — ' . config('app.name'))
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
            <h1 class="font-serif font-black text-2xl text-[#111] mb-1">লগইন</h1>
            <p class="text-sm text-[#666] mb-6">আপনার অ্যাকাউন্টে প্রবেশ করুন</p>

            @if(session('status'))
            <div class="mb-4 border-l-4 border-[#111] bg-[#f5f5f5] px-4 py-3 text-sm">{{ session('status') }}</div>
            @endif

            @if($errors->any())
            <div class="mb-4 border-l-4 border-[#E02020] bg-red-50 px-4 py-3 text-sm text-[#E02020]">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-[#111] mb-1.5">ইমেইল</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full border border-[#e5e5e5] focus:border-[#111] focus:outline-none focus:ring-1 focus:ring-[#111] px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-[#111] mb-1.5">পাসওয়ার্ড</label>
                    <input type="password" name="password" required class="w-full border border-[#e5e5e5] focus:border-[#111] focus:outline-none focus:ring-1 focus:ring-[#111] px-3 py-2.5 text-sm">
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-[#444]">
                        <input type="checkbox" name="remember" class="border-[#111] text-[#111] focus:ring-[#111]">
                        মনে রাখুন
                    </label>
                    @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-[#111] hover:underline">পাসওয়ার্ড ভুলে গেছেন?</a>
                    @endif
                </div>
                <button type="submit" class="w-full bg-[#111] hover:bg-black text-white text-sm font-semibold uppercase tracking-wider py-3 transition">লগইন</button>
            </form>

            @if(Route::has('register'))
            <p class="text-sm text-center text-[#666] mt-5 pt-5 border-t border-[#e5e5e5]">
                অ্যাকাউন্ট নেই? <a href="{{ route('register') }}" class="text-[#111] font-semibold hover:underline">রেজিস্টার করুন</a>
            </p>
            @endif
        </div>

        <p class="text-center text-xs text-[#999] mt-6">
            <a href="/" class="hover:text-[#111] transition">← হোমপেজে ফিরুন</a>
        </p>
@endsection
