@extends('layouts.guest')
@section('title', 'নতুন পাসওয়ার্ড — ' . config('app.name'))
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
            <h1 class="font-serif font-black text-2xl text-[#111] mb-1">নতুন পাসওয়ার্ড</h1>
            <p class="text-sm text-[#666] mb-6">আপনার নতুন পাসওয়ার্ডটি নির্ধারণ করুন</p>

            @if($errors->any())
            <div class="mb-4 border-l-4 border-[#E02020] bg-red-50 px-4 py-3 text-sm text-[#E02020]">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-[#111] mb-1.5">ইমেইল</label>
                    <input type="email" name="email" value="{{ old('email', $email) }}" required class="w-full border border-[#e5e5e5] focus:border-[#111] focus:outline-none focus:ring-1 focus:ring-[#111] px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-[#111] mb-1.5">নতুন পাসওয়ার্ড</label>
                    <input type="password" name="password" required minlength="8" autocomplete="new-password" class="w-full border border-[#e5e5e5] focus:border-[#111] focus:outline-none focus:ring-1 focus:ring-[#111] px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-[#111] mb-1.5">পাসওয়ার্ড নিশ্চিত করুন</label>
                    <input type="password" name="password_confirmation" required minlength="8" autocomplete="new-password" class="w-full border border-[#e5e5e5] focus:border-[#111] focus:outline-none focus:ring-1 focus:ring-[#111] px-3 py-2.5 text-sm">
                </div>
                <button type="submit" class="w-full bg-[#111] hover:bg-black text-white text-sm font-semibold uppercase tracking-wider py-3 transition">পাসওয়ার্ড রিসেট করুন</button>
            </form>
        </div>

        <p class="text-center text-xs text-[#999] mt-6">
            <a href="{{ route('login') }}" class="hover:text-[#111] transition">← লগইনে ফিরুন</a>
        </p>
</div>
@endsection
