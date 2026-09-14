@extends('layouts.admin')
@section('title', 'Bulk SEO Editor')
@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <div class="flex items-center gap-2">
            <svg class="h-5 w-5 text-[#999]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            <h1 class="text-2xl font-bold">Bulk SEO Editor</h1>
        </div>
        <p class="text-xs text-[#999] mt-0.5">{{ $articles->total() }} Posts ({{ $total }} Total)</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.seo.dashboard') }}" class="border border-[#e0e0e0] dark:border-[#444] text-[#666] dark:text-[#aaa] px-4 py-2 text-xs font-medium hover:bg-[#f5f5f5] dark:hover:bg-[#2a2a2a] transition flex items-center gap-1">
            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            SEO Dashboard
        </a>
    </div>
</div>

<form method="GET" action="{{ route('admin.seo.bulk-editor') }}" class="flex flex-wrap items-center gap-2 mb-5">
    <input type="text" name="search" placeholder="Search Title..." value="{{ request('search') }}" class="admin-input min-w-[200px]">
    <select name="status" class="admin-select">
        <option value="">All Statuses</option>
        <option value="published" @selected(request('status') === 'published')>Published</option>
        <option value="draft" @selected(request('status') === 'draft')>Draft</option>
        <option value="submitted" @selected(request('status') === 'submitted')>Pending Review</option>
    </select>
    <select name="category_id" class="admin-select">
        <option value="">All Categories</option>
        @foreach($categories as $cat)
        <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name_bn }}</option>
        @endforeach
    </select>
    <select name="seo_issue" class="admin-select">
        <option value="">All SEO Issues</option>
        <option value="no_meta_title" @selected(request('seo_issue') === 'no_meta_title')>No Meta Title</option>
        <option value="no_meta_desc" @selected(request('seo_issue') === 'no_meta_desc')>No Meta Description</option>
        <option value="no_og_image" @selected(request('seo_issue') === 'no_og_image')>No OG Image</option>
        <option value="no_keywords" @selected(request('seo_issue') === 'no_keywords')>No Keywords</option>
    </select>
    <button type="submit" class="btn-primary text-sm">Filter</button>
    <a href="{{ route('admin.seo.bulk-editor') }}" class="text-xs text-[#999] hover:text-[#E02020]">Reset</a>
</form>

<form method="POST" action="{{ route('admin.seo.bulk-update') }}" id="bulkSeoForm">
    @csrf
    <div class="admin-card overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="admin-table-header">
                <tr>
                    <th class="text-left p-2.5 font-semibold text-[#666] text-xs uppercase tracking-wider w-[200px] min-w-[180px]">Posts</th>
                    <th class="text-left p-2.5 font-semibold text-[#666] text-xs uppercase tracking-wider min-w-[200px]">Meta Title <span class="text-[10px] font-normal text-[#999]">(30-60 chars)</span></th>
                    <th class="text-left p-2.5 font-semibold text-[#666] text-xs uppercase tracking-wider min-w-[220px]">Meta Description <span class="text-[10px] font-normal text-[#999]">(50-160 chars)</span></th>
                    <th class="text-left p-2.5 font-semibold text-[#666] text-xs uppercase tracking-wider min-w-[150px]">Keywords</th>
                    <th class="text-left p-2.5 font-semibold text-[#666] text-xs uppercase tracking-wider min-w-[120px]">OG Image URL</th>
                    <th class="text-center p-2.5 font-semibold text-[#666] text-xs uppercase tracking-wider w-[60px]">Index</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e0e0e0] dark:divide-[#333]">
                @forelse($articles as $article)
                <tr class="admin-hover-row">
                    <td class="p-2.5">
                        <input type="hidden" name="articles[{{ $loop->index }}][id]" value="{{ $article->id }}">
                        <p class="text-xs font-medium leading-snug">{{ Str::limit($article->title_bn, 40) }}</p>
                        <p class="text-[10px] text-[#999]">{{ $article->category?->name_bn }}</p>
                    </td>
                    <td class="p-2.5">
                        <input type="text" name="articles[{{ $loop->index }}][meta_title]" value="{{ $article->meta_title ?? $article->title_bn }}" class="admin-input w-full text-xs p-1.5 meta-title-input" data-index="{{ $loop->index }}">
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-[10px] char-count-{{ $loop->index }} {{ mb_strlen($article->meta_title ?? $article->title_bn) >= 30 && mb_strlen($article->meta_title ?? $article->title_bn) <= 60 ? 'text-green-600' : 'text-red-500' }}">{{ mb_strlen($article->meta_title ?? $article->title_bn) }} chars</span>
                            @if(mb_strlen($article->meta_title ?? $article->title_bn) < 30)<span class="text-[10px] text-red-500">Too short</span>@endif
                            @if(mb_strlen($article->meta_title ?? $article->title_bn) > 60)<span class="text-[10px] text-red-500">Too long</span>@endif
                        </div>
                    </td>
                    <td class="p-2.5">
                        <textarea name="articles[{{ $loop->index }}][meta_description]" rows="2" class="admin-input w-full text-xs p-1.5">{{ $article->meta_description ?? '' }}</textarea>
                    </td>
                    <td class="p-2.5">
                        <input type="text" name="articles[{{ $loop->index }}][focus_keywords]" value="{{ $article->focus_keywords }}" class="admin-input w-full text-xs p-1.5" placeholder="Separate with commas">
                    </td>
                    <td class="p-2.5">
                        <input type="text" name="articles[{{ $loop->index }}][og_image]" value="{{ $article->og_image ?? $article->featured_image }}" class="admin-input w-full text-xs p-1.5" placeholder="URL">
                    </td>
                    <td class="p-2.5 text-center">
                        <input type="hidden" name="articles[{{ $loop->index }}][indexable]" value="0">
                        <input type="checkbox" name="articles[{{ $loop->index }}][indexable]" value="1" @checked($article->indexable) class="accent-[#0d0d0d]">
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="p-8 text-center text-sm text-[#999]">No Posts found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4 flex items-center justify-between">
        <div>{{ $articles->links() }}</div>
        <button type="submit" class="btn-primary">Save SEO Updates</button>
    </div>
</form>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.meta-title-input').forEach(input => {
    input.addEventListener('input', function() {
        const idx = this.dataset.index;
        const len = this.value.length;
        const el = document.querySelector('.char-count-' + idx);
        if (el) {
            el.textContent = len + ' chars';
            el.className = 'text-[10px] char-count-' + idx + ' ' + (len >= 30 && len <= 60 ? 'text-green-600' : 'text-red-500');
        }
    });
});
</script>
@endpush
