@extends('layouts.admin')
@section('title', 'Slider Manager')
@section('content')
<div class="flex items-center justify-between mb-5">
    <div>
        <div class="flex items-center gap-2">
            <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            <h1 class="text-2xl font-bold">Slider Manager</h1>
        </div>
        <p class="text-xs text-[#999] mt-0.5">{{ $articles->count() }} Slider Posts</p>
    </div>
    <a href="{{ route('admin.posts.index') }}" class="border border-[#e0e0e0] text-[#666] px-4 py-2 text-xs font-medium hover:bg-[#f5f5f5] transition flex items-center gap-1">
        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        All Posts
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="admin-card">
            <div class="px-5 py-3 border-b border-[#e0e0e0] dark:border-[#333] flex items-center justify-between">
                <h2 class="text-sm font-bold">Slider Posts</h2>
                <span class="text-xs text-[#999]">Drag to reorder</span>
            </div>
            <div id="sliderList" class="divide-y divide-[#e0e0e0] dark:divide-[#333]">
                @forelse($articles as $article)
                <div class="flex items-center gap-3 px-5 py-3 admin-hover-row slider-item" data-id="{{ $article->id }}" data-order="{{ $article->slider_order }}">
                    <span class="text-[#ccc] text-sm drag-handle">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                    </span>
                    <div class="w-14 h-14 bg-[#f5f5f5] dark:bg-[#2a2a2a] border border-[#e0e0e0] dark:border-[#333] shrink-0 overflow-hidden flex items-center justify-center text-[#ccc] text-xs">
                        @if($article->featured_image)
                        <img src="{{ $article->featured_image_url }}" alt="" class="w-full h-full object-cover">
                        @else
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium truncate">{{ $article->title_bn }}</p>
                        <p class="text-xs text-[#999]">{{ $article->category?->name_bn }} — {{ $article->published_at?->format('d/m/Y') }}</p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <button type="button" onclick="removeFromSlider({{ $article->id }})" class="text-xs text-[#999] hover:text-[#E02020] transition px-2 py-1 border border-[#e0e0e0] dark:border-[#444] hover:border-red-300" title="Remove from Slider">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
                @empty
                <div class="p-10 text-center text-sm text-[#999]">
                    <p class="mb-1">No Slider Posts</p>
                    <p class="text-xs">Add Posts from the list below to the Slider</p>
                </div>
                @endforelse
            </div>
        </div>
        <div class="mt-4 text-right">
            <button type="button" onclick="saveSliderOrder()" class="btn-primary" id="saveOrderBtn" style="display:none">Save Order</button>
        </div>
    </div>

    <div>
        <div class="admin-card">
            <div class="px-5 py-3 border-b border-[#e0e0e0] dark:border-[#333]">
                <h2 class="text-sm font-bold">Add Posts</h2>
            </div>
            <div class="divide-y divide-[#e0e0e0] dark:divide-[#333] max-h-[600px] overflow-y-auto">
                @forelse($available as $article)
                <div class="flex items-center gap-3 px-5 py-3 admin-hover-row">
                    <div class="w-10 h-10 bg-[#f5f5f5] dark:bg-[#2a2a2a] border border-[#e0e0e0] dark:border-[#333] shrink-0 overflow-hidden flex items-center justify-center text-[#ccc] text-xs">
                        @if($article->featured_image)
                        <img src="{{ $article->featured_image_url }}" alt="" class="w-full h-full object-cover">
                        @else
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-medium truncate">{{ Str::limit($article->title_bn, 35) }}</p>
                        <p class="text-[10px] text-[#999]">{{ $article->category?->name_bn }}</p>
                    </div>
                    <div class="inline shrink-0">
                        <button type="button" onclick="addToSlider({{ $article->id }})" class="text-xs text-blue-600 dark:text-blue-400 hover:text-blue-800 font-medium whitespace-nowrap flex items-center gap-0.5">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Slider
                        </button>
                    </div>
                </div>
                @empty
                <div class="p-6 text-center text-sm text-[#999]">All Published Posts are already in the Slider</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let dragSrcEl = null;
document.querySelectorAll('.slider-item').forEach(item => {
    item.setAttribute('draggable', 'true');
    item.addEventListener('dragstart', function(e) {
        dragSrcEl = this;
        this.classList.add('opacity-50');
    });
    item.addEventListener('dragend', function(e) {
        this.classList.remove('opacity-50');
    });
    item.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('bg-[#f0f0f0]');
    });
    item.addEventListener('dragleave', function(e) {
        this.classList.remove('bg-[#f0f0f0]');
    });
    item.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('bg-[#f0f0f0]');
        if (dragSrcEl !== this) {
            const parent = document.getElementById('sliderList');
            const items = [...parent.querySelectorAll('.slider-item')];
            const fromIdx = items.indexOf(dragSrcEl);
            const toIdx = items.indexOf(this);
            if (fromIdx < toIdx) {
                parent.insertBefore(dragSrcEl, this.nextSibling);
            } else {
                parent.insertBefore(dragSrcEl, this);
            }
            document.getElementById('saveOrderBtn').style.display = 'inline-block';
        }
    });
});

function saveSliderOrder() {
    const items = [...document.querySelectorAll('.slider-item')];
    const orderData = items.map((item, idx) => ({ id: parseInt(item.dataset.id), order: idx }));
    fetch('{{ route("admin.posts.slider-reorder") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ items: orderData })
    }).then(r => r.json()).then(d => {
        if (d.success) {
            document.getElementById('saveOrderBtn').style.display = 'none';
            location.reload();
        }
    });
}

function addToSlider(id) { toggleArticleFlag(id, 'is_slider'); }
function removeFromSlider(id) { if (confirm('Remove from Slider?')) toggleArticleFlag(id, 'is_slider'); }
function toggleArticleFlag(id, flag) {
    fetch('{{ route("admin.posts.toggle-flag", ["article" => 0]) }}'.replace('/0', '/' + id), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ flag })
    }).then(r => r.json()).then(d => { if (d.success) location.reload(); });
}
</script>
@endpush
