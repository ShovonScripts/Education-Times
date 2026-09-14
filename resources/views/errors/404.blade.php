@extends('layouts.app')

@section('title', 'পেজ খুঁ পাওয়া যায়নি — ' . config('app.name'))

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4">
    <div class="text-center max-w-lg">
        <div class="font-serif font-black text-8xl text-[#E02020] mb-4 leading-none">৪০৪</div>
        <h1 class="text-2xl font-serif font-black text-[#111] mb-2">পেজটি খুঁজে পাওয়া যায়নি</h1>
        <p class="text-sm text-[#666] mb-8">আপনি যে পেজটি খুঁজছেন তা বিদ্যমান নেই বা সরিয়ে দেওয়া হয়েছে।</p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center items-center">
            <a href="{{ route('home') }}" class="bg-[#111] hover:bg-black text-white text-sm font-semibold uppercase tracking-wider py-2.5 px-6 transition">প্রথম পাতায় ফিরুন</a>
            <a href="{{ route('news.index') }}" class="border border-[#111] text-[#111] hover:bg-[#111] hover:text-white text-sm font-semibold uppercase tracking-wider py-2.5 px-6 transition">সব খবর</a>
        </div>
        <form method="GET" action="{{ route('search.index') }}" class="flex mt-8 max-w-sm mx-auto">
            <input type="text" name="q" placeholder="খবর খুঁজুন..." class="flex-1 border border-[#e5e5e5] focus:border-[#111] focus:outline-none px-4 py-2.5 text-sm">
            <button type="submit" class="bg-[#E02020] hover:bg-[#c01818] text-white text-sm font-semibold px-5 transition">খুঁজুন</button>
        </form>
    </div>
</div>
@endsection
