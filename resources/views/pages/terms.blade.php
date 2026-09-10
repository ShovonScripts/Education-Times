@extends('layouts.app')
@section('title', 'শর্তাবলী — ' . config('app.name'))
@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <header class="border-b-2 border-[#111] pb-3 mb-6">
        <h1 class="font-serif font-black text-3xl text-[#111]">ব্যবহারের শর্তাবলী</h1>
        <p class="text-xs text-[#666] mt-1">সর্বশেষ আপডেট: {{ now()->locale('bn')->translatedFormat('j F Y') }}</p>
    </header>
    <article class="prose-bn [&_h2]:text-[#111] [&_h3]:text-[#111] [&_a]:text-[#111]">
        {!! $content !!}
    </article>
</div>
@endsection
