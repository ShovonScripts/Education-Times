@extends('layouts.admin')
@section('title', $titleBn . ' — Edit')
@push('editor')
<x-head.editor-config/>
@endpush
@section('content')
<div class="mb-6">
    <a href="{{ route('admin.pages.index') }}" class="text-xs text-[#999] hover:text-[#0d0d0d] dark:hover:text-white transition">
        <span class="flex items-center gap-1">
            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Go Back
        </span>
    </a>
</div>
<h1 class="font-serif text-2xl font-bold mb-6">{{ $titleBn }} <span class="text-sm font-normal text-[#999]">({{ $slug }})</span></h1>

<div class="bg-white dark:bg-[#1a1a1a] border border-[#e0e0e0] dark:border-[#333] p-6 md:p-8">
    <form method="POST" action="{{ route('admin.pages.update', $slug) }}">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium text-[#666] dark:text-[#aaa] mb-1">Content</label>
            <textarea name="content" data-editor rows="20"
                class="w-full border border-[#e0e0e0] dark:border-[#444] px-4 py-2.5 text-sm">{{ old('content', $content) }}</textarea>
        </div>
        <div class="flex justify-end mt-6">
            <button type="submit" class="btn-primary">Save</button>
        </div>
    </form>
</div>


@endsection
