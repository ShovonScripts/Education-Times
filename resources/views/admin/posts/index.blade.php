@extends('layouts.admin')
@section('title', 'Posts Manager')
@section('content')
<div class="flex items-center justify-between mb-5">
    <div>
        <h1 class="font-serif text-2xl font-bold">Posts Manager</h1>
        <p class="text-xs text-[#999] mt-0.5">{{ $articles->total() }} টি Posts</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.posts.slider') }}" class="border border-[#e0e0e0] text-[#666] px-3 py-2 text-xs font-medium hover:bg-[#f5f5f5] transition flex items-center gap-1">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Slider
        </a>
        <a href="{{ route('admin.posts.breaking') }}" class="border border-[#e0e0e0] text-[#666] px-3 py-2 text-xs font-medium hover:bg-[#f5f5f5] transition flex items-center gap-1">
            <svg class="h-3.5 w-3.5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            Breaking
        </a>
        <a href="{{ route('admin.articles.create') }}" class="btn-primary flex items-center gap-1.5">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            নতুন
        </a>
    </div>
</div>

{{-- Tabs --}}
<div class="flex gap-0.5 mb-5 text-sm flex-wrap border-b border-[#e0e0e0]">
    <a href="{{ route('admin.posts.index') }}" class="px-4 py-2.5 font-medium transition border-b-2 @if(!request('status') && !request('flag')) border-[#E02020] text-[#E02020] @else border-transparent text-[#999] hover:text-[#666] @endif">সব ({{ $counts['all'] }})</a>
    <a href="{{ route('admin.posts.index', ['status' => 'published']) }}" class="px-4 py-2.5 font-medium transition border-b-2 @if(request('status') === 'published') border-[#E02020] text-[#E02020] @else border-transparent text-[#999] hover:text-[#666] @endif">Published ({{ $counts['published'] }})</a>
    <a href="{{ route('admin.posts.index', ['status' => 'submitted']) }}" class="px-4 py-2.5 font-medium transition border-b-2 @if(request('status') === 'submitted') border-[#E02020] text-[#E02020] @else border-transparent text-[#999] hover:text-[#666] @endif">Pending Review @if($counts['submitted'] > 0)<span class="ml-1 bg-yellow-400 text-black text-[10px] px-1.5 py-0.5 rounded">{{ $counts['submitted'] }}</span>@endif</a>
    <a href="{{ route('admin.posts.index', ['status' => 'draft']) }}" class="px-4 py-2.5 font-medium transition border-b-2 @if(request('status') === 'draft') border-[#E02020] text-[#E02020] @else border-transparent text-[#999] hover:text-[#666] @endif">Draft ({{ $counts['draft'] }})</a>
    <a href="{{ route('admin.posts.index', ['status' => 'scheduled']) }}" class="px-4 py-2.5 font-medium transition border-b-2 @if(request('status') === 'scheduled') border-[#E02020] text-[#E02020] @else border-transparent text-[#999] hover:text-[#666] @endif">Scheduled ({{ $counts['scheduled'] }})</a>

</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.posts.index') }}" class="flex flex-wrap items-center gap-2 mb-5">
    @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
    <input type="text" name="search" placeholder="Title বা Excerptে খুঁজুন..." value="{{ request('search') }}" class="border border-[#e0e0e0] px-3 py-2 text-sm min-w-[220px] focus:outline-none focus:border-[#0d0d0d]">
    <select name="category_id" class="border border-[#e0e0e0] px-3 py-2 text-sm bg-white focus:outline-none focus:border-[#0d0d0d]">
        <option value="">সব Categories</option>
        @foreach($categories as $cat)
        <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name_bn }}</option>
        @endforeach
    </select>
    <select name="author_id" class="border border-[#e0e0e0] px-3 py-2 text-sm bg-white focus:outline-none focus:border-[#0d0d0d]">
        <option value="">সব Author</option>
        @foreach($authors as $author)
        <option value="{{ $author->id }}" @selected(request('author_id') == $author->id)>{{ $author->name }}</option>
        @endforeach
    </select>
    <input type="date" name="date_from" value="{{ request('date_from') }}" class="border border-[#e0e0e0] px-3 py-2 text-sm focus:outline-none focus:border-[#0d0d0d]">
    <input type="date" name="date_to" value="{{ request('date_to') }}" class="border border-[#e0e0e0] px-3 py-2 text-sm focus:outline-none focus:border-[#0d0d0d]">
    <button type="submit" class="bg-[#0d0d0d] dark:bg-[#333] text-white px-4 py-2 text-sm font-medium hover:bg-black dark:hover:bg-[#444] transition">ফিল্টার</button>
    @if(request()->anyFilled(['search', 'category_id', 'author_id', 'date_from', 'date_to']))
    <a href="{{ route('admin.posts.index', request()->only(['status'])) }}" class="text-xs text-[#999] hover:text-[#E02020] transition">ফিল্টার রিসেট</a>
    @endif
</form>

{{-- Bulk Actions --}}
<div class="mb-3 hidden" id="bulkBar">
    <div class="flex items-center gap-2 bg-[#f5f5f5] border border-[#e0e0e0] px-4 py-2.5 text-sm">
        <span class="text-[#666]"><span id="bulkCount">0</span> টি নির্বাচিত</span>
        <span class="text-[#ccc]">|</span>
        <button type="button" onclick="bulkAction('publish')" class="text-green-700 hover:text-green-900 text-xs font-medium">প্রকাশ</button>
        <button type="button" onclick="bulkAction('draft')" class="text-[#666] hover:text-[#0d0d0d] text-xs font-medium">Draft</button>
        <button type="button" onclick="bulkAction('archive')" class="text-[#666] hover:text-[#0d0d0d] text-xs font-medium">আর্কাইভ</button>
        <span class="text-[#ccc]">|</span>
        <button type="button" onclick="bulkAction('breaking')" class="text-red-600 hover:text-red-800 text-xs font-medium">Breaking</button>
        <button type="button" onclick="bulkAction('featured')" class="text-yellow-600 hover:text-yellow-800 text-xs font-medium">Featured</button>
        <button type="button" onclick="bulkAction('slider')" class="text-blue-600 hover:text-blue-800 text-xs font-medium">Slider</button>
        <span class="text-[#ccc]">|</span>
        <button type="button" onclick="if(confirm('নিশ্চিত?')) bulkAction('delete')" class="text-[#E02020] hover:text-red-700 text-xs font-medium">ডিলিট</button>
        <form id="bulkForm" method="POST" action="{{ route('admin.posts.bulk') }}" class="hidden">
            @csrf
            <input type="hidden" name="action" id="bulkActionInput">
            <div id="bulkIds"></div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="bg-white border border-[#e0e0e0] overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-[#fafafa] border-b border-[#e0e0e0]">
            <tr>
                <th class="w-10 p-3">
                    <input type="checkbox" id="selectAll" class="accent-[#E02020]">
                </th>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Posts</th>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider hidden md:table-cell">Categories</th>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider hidden lg:table-cell">Author</th>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Status</th>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider hidden sm:table-cell">দেখা</th>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider hidden lg:table-cell">Date</th>
                <th class="text-right p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#e0e0e0]">
            @forelse($articles as $article)
            <tr class="hover:bg-[#fafafa] transition group" data-id="{{ $article->id }}">
                <td class="p-3">
                    <input type="checkbox" class="row-checkbox accent-[#E02020]" value="{{ $article->id }}">
                </td>
                <td class="p-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-[#f5f5f5] border border-[#e0e0e0] shrink-0 flex items-center justify-center text-[#ccc] text-xs overflow-hidden">
                            @if($article->featured_image)
                            <img src="{{ $article->featured_image_url }}" alt="" class="w-full h-full object-cover">
                            @else
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <a href="{{ route('admin.articles.edit', $article) }}" class="font-medium text-[#0d0d0d] hover:text-[#E02020] transition truncate block leading-snug">{{ Str::limit($article->title_bn, 55) }}</a>
                            <div class="flex items-center gap-1.5 mt-0.5 flex-wrap">
                                @if($article->is_breaking)<span class="text-[9px] font-bold uppercase bg-red-600 text-white px-1 py-0.5 leading-tight">BREAKING</span>@endif
                                @if($article->is_featured)<span class="text-[9px] font-bold uppercase bg-yellow-500 text-white px-1 py-0.5 leading-tight">FEATURED</span>@endif
                                @if($article->is_slider)<span class="text-[9px] font-bold uppercase bg-blue-600 text-white px-1 py-0.5 leading-tight">SLIDER</span>@endif
                                @if($article->is_editor_pick)<span class="text-[9px] font-bold uppercase bg-[#0d0d0d] text-white px-1 py-0.5 leading-tight">PICK</span>@endif
                            </div>
                        </div>
                    </div>
                </td>
                <td class="p-3 text-[#666] hidden md:table-cell text-xs">{{ $article->category?->name_bn ?? '—' }}</td>
                <td class="p-3 text-[#666] hidden lg:table-cell text-xs">{{ $article->staffs->first()?->name_bn ?? $article->author?->name ?? '—' }}</td>
                <td class="p-3">
                    <div class="flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full inline-block
                            @if($article->status === 'published') bg-green-500
                            @elseif($article->status === 'submitted') bg-yellow-500
                            @elseif($article->status === 'scheduled') bg-blue-500
                            @else bg-gray-400 @endif">
                        </span>
                        <span class="text-xs
                            @if($article->status === 'published') text-green-700
                            @elseif($article->status === 'submitted') text-yellow-700
                            @elseif($article->status === 'scheduled') text-blue-700
                            @else text-[#666] @endif">
                            {{ $article->status === 'published' ? 'Published' : ($article->status === 'submitted' ? 'Pending Review' : ($article->status === 'scheduled' ? 'Scheduled' : 'Draft')) }}
                        </span>
                    </div>
                </td>
                <td class="p-3 text-[#999] text-xs hidden sm:table-cell">{{ number_format($article->view_counts_sum_views ?? 0) }}</td>
                <td class="p-3 text-[#999] text-xs hidden lg:table-cell">{{ $article->published_at?->format('d/m/Y H:i') ?? $article->created_at->format('d/m/Y') }}</td>
                <td class="p-3 text-right">
                    <div class="flex items-center justify-end gap-1">
                        <a href="{{ route('article.show', $article->slug) }}" target="_blank" class="text-[#999] hover:text-[#0d0d0d] p-1.5 transition" title="প্রিভিউ">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                        <button type="button" onclick="toggleFlag({{ $article->id }}, 'is_breaking')" class="p-1.5 transition @if($article->is_breaking) text-red-600 hover:text-red-800 @else text-[#ccc] hover:text-[#999] @endif" title="Breaking টগল">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                        </button>
                        <button type="button" onclick="toggleFlag({{ $article->id }}, 'is_featured')" class="p-1.5 transition @if($article->is_featured) text-yellow-500 hover:text-yellow-700 @else text-[#ccc] hover:text-[#999] @endif" title="Featured টগল">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        </button>
                        <button type="button" onclick="toggleFlag({{ $article->id }}, 'is_slider')" class="p-1.5 transition @if($article->is_slider) text-blue-600 hover:text-blue-800 @else text-[#ccc] hover:text-[#999] @endif" title="Slider টগল">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </button>
                        <a href="{{ route('admin.articles.edit', $article) }}" class="text-[#666] hover:text-[#0d0d0d] p-1.5 transition" title="এডিট">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <form method="POST" action="{{ route('admin.articles.destroy', $article) }}" class="inline" onsubmit="return confirm('নিশ্চিত?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-[#E02020] hover:text-red-700 p-1.5 transition" title="ডিলিট">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="p-10 text-center">
                    <div class="text-[#ccc] mb-2">
                        <svg class="h-10 w-10 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    </div>
                    <p class="text-sm text-[#999]">কোনো Posts পাওয়া যায়নি</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $articles->links() }}</div>
@endsection

@push('scripts')
<script>
document.getElementById('selectAll')?.addEventListener('change', function() {
    document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = this.checked);
    updateBulkBar();
});
document.querySelectorAll('.row-checkbox').forEach(cb => {
    cb.addEventListener('change', updateBulkBar);
});
function updateBulkBar() {
    const checked = document.querySelectorAll('.row-checkbox:checked');
    const bar = document.getElementById('bulkBar');
    if (checked.length > 0) {
        bar.classList.remove('hidden');
        document.getElementById('bulkCount').textContent = checked.length;
    } else {
        bar.classList.add('hidden');
    }
}
function bulkAction(action) {
    const checked = document.querySelectorAll('.row-checkbox:checked');
    if (checked.length === 0) return;
    if (action === 'delete' && !confirm('{{ $articles->count() }} টি Posts ডিলিট করবেন?')) return;
    document.getElementById('bulkActionInput').value = action;
    const container = document.getElementById('bulkIds');
    container.innerHTML = '';
    checked.forEach(cb => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ids[]';
        input.value = cb.value;
        container.appendChild(input);
    });
    document.getElementById('bulkForm').submit();
}
function toggleFlag(id, flag) {
    fetch('{{ route("admin.posts.toggle-flag", ["article" => "__ID__"]) }}'.replace('__ID__', id), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ flag })
    }).then(r => r.json()).then(d => {
        if (d.success) location.reload();
    });
}
</script>
@endpush
