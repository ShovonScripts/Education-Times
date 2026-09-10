@extends('layouts.guest')

@section('title', 'এডমিন লগইন - ' . config('app.name'))

@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-sm">
        <div class="text-center mb-8">
            <a href="/" class="font-serif font-bold text-4xl text-[#0d0d0d] dark:text-[#e0e0e0]">ET</a>
            <p class="text-[#E02020] text-sm mt-1 font-semibold">এডমিন প্যানেল</p>
        </div>

        <div class="bg-white dark:bg-[#1e1e1e] rounded-sm shadow-sm border border-[#e0e0e0] dark:border-[#333] p-8">
            <h1 class="text-2xl font-bold mb-6 dark:text-white">এডমিন লগইন</h1>

            @if ($errors->any())
                <div class="bg-red-50 dark:bg-red-950/20 border-l-4 border-[#E02020] p-4 mb-6">
                    <ul class="text-sm text-[#E02020] dark:text-red-400 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.attempt') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-[#666] dark:text-[#999] mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full border border-[#e0e0e0] dark:border-[#444] px-4 py-2.5 text-sm focus:outline-none focus:border-[#E02020] dark:focus:border-[#ff6b6b] transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#666] dark:text-[#999] mb-1">Password</label>
                    <input type="password" name="password" required
                        class="w-full border border-[#e0e0e0] dark:border-[#444] px-4 py-2.5 text-sm focus:outline-none focus:border-[#E02020] dark:focus:border-[#ff6b6b] transition">
                </div>
                <button type="submit"
                    class="w-full bg-[#E02020] dark:bg-[#cc1a1a] text-white py-3 font-medium hover:bg-red-700 dark:hover:bg-[#b71c1c] transition text-sm">
                    লগইন
                </button>
            </form>

            <p class="text-center text-xs text-[#999] dark:text-[#777] mt-6">
                <a href="{{ route('login') }}" class="hover:text-[#E02020] dark:hover:text-[#ff6b6b] transition">ইউজার লগইন →</a>
            </p>
        </div>
    </div>
</div>
@endsection
