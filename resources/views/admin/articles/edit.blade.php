@extends('layouts.admin')
@section('title', 'Edit Article')
@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.articles.index') }}" class="text-xs text-[#999] hover:text-[#0d0d0d] transition"><span class="flex items-center gap-1"><svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg> Go Back</span></a>
    </div>
    <h1 class="font-serif text-2xl font-bold mb-6">Edit Article</h1>
    <div class="bg-white dark:bg-[#1a1a1a] border border-[#e0e0e0] dark:border-[#333] p-6 md:p-8">
        <form method="POST" action="{{ route('admin.articles.update', $article) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-[#666] dark:text-[#aaa] mb-1">Title (Bengali) *</label>
                <input type="text" name="title_bn" value="{{ old('title_bn', $article->title_bn) }}" required
                    class="w-full border border-[#e0e0e0] dark:border-[#444] dark:bg-[#222] dark:text-[#eee] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d] dark:focus:border-white">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-[#666] dark:text-[#aaa] mb-1">Categories *</label>
                    <select name="category_id" required class="w-full border border-[#e0e0e0] dark:border-[#444] dark:bg-[#222] dark:text-[#eee] px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-[#0d0d0d] dark:focus:border-white">
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected($article->category_id == $cat->id)>{{ $cat->name_bn }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#666] dark:text-[#aaa] mb-1">Reporter (hold ctrl+click to select multiple)</label>
                    <select name="staff_ids[]" multiple
                        class="w-full border border-[#e0e0e0] dark:border-[#444] dark:bg-[#222] dark:text-[#eee] px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-[#0d0d0d] dark:focus:border-white min-h-[100px]">
                        @foreach($staff as $s)
                        <option value="{{ $s->id }}" @selected(in_array($s->id, old('staff_ids', $article->staffs->pluck('id')->toArray())))>{{ $s->name_bn }} — {{ $s->designation_bn }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-[#666] dark:text-[#aaa] mb-1">District</label>
                    <select name="district_id" class="w-full border border-[#e0e0e0] dark:border-[#444] dark:bg-[#222] dark:text-[#eee] px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-[#0d0d0d] dark:focus:border-white">
                        <option value="">—</option>
                        @foreach($districts as $d)
                        <option value="{{ $d->id }}" @selected($article->district_id == $d->id)>{{ $d->name_bn }}</option>
                        @endforeach
                    </select>
                </div>
                <div></div>
            </div>
            <div>
                <label class="block text-sm font-medium text-[#666] dark:text-[#aaa] mb-1">Excerpt</label>
                <textarea name="excerpt_bn" rows="2"
                    class="w-full border border-[#e0e0e0] dark:border-[#444] dark:bg-[#222] dark:text-[#eee] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d] dark:focus:border-white">{{ old('excerpt_bn', $article->excerpt_bn) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-[#666] dark:text-[#aaa] mb-1">Body *</label>
                <textarea name="body_bn" data-editor required
                    class="w-full border border-[#e0e0e0] dark:border-[#444] px-4 py-2.5 text-sm">{{ old('body_bn', $article->body_bn) }}</textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-[#666] dark:text-[#aaa] mb-1">Featured Image (JPEG/PNG/WebP)</label>
                    <input type="file" name="featured_image" accept="image/*"
                        class="w-full border border-[#e0e0e0] dark:border-[#444] dark:bg-[#222] dark:text-[#eee] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d] dark:focus:border-white file:mr-3 file:border-0 file:bg-[#f5f5f5] dark:file:bg-[#333] file:px-3 file:py-1.5 file:text-xs file:font-medium">
                    @if(isset($article) && $article->featured_image_url)
                    <div class="mt-2 flex items-center gap-3">
                        <img src="{{ $article->featured_image_url }}" alt="" class="w-24 h-16 object-cover border border-[#e0e0e0] dark:border-[#444]">
                        <label class="flex items-center gap-2 text-xs text-[#999] cursor-pointer">
                            <input type="checkbox" name="remove_featured_image" value="1" class="accent-[#E02020]"> Remove current image
                        </label>
                    </div>
                    @endif
                    <p class="text-xs text-[#999] mt-1">Uploading a new image automatically deletes the old one.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#666] dark:text-[#aaa] mb-1">YouTube Video Link</label>
                    <input type="text" name="video_url" value="{{ old('video_url', $article->video_url) }}" placeholder="https://youtube.com/watch?v=..."
                        class="w-full border border-[#e0e0e0] dark:border-[#444] dark:bg-[#222] dark:text-[#eee] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d] dark:focus:border-white">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-[#666] dark:text-[#aaa] mb-1">Scheduled Publish Time</label>
                <input type="datetime-local" name="published_at" value="{{ old('published_at', $article->published_at?->format('Y-m-d\TH:i')) }}"
                    class="w-full border border-[#e0e0e0] dark:border-[#444] dark:bg-[#222] dark:text-[#eee] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d] dark:focus:border-white">
                <p class="text-xs text-[#999] mt-1">Fill in only for scheduled publishing</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-[#666] dark:text-[#aaa] mb-1">Tags (comma separated)</label>
                <input type="text" name="tags" value="{{ old('tags', $article->tags->pluck('tag')->join(', ')) }}"
                    class="w-full border border-[#e0e0e0] dark:border-[#444] dark:bg-[#222] dark:text-[#eee] px-4 py-2.5 text-sm focus:outline-none focus:border-[#0d0d0d] dark:focus:border-white">
            </div>
            <div class="flex items-center gap-6 pt-2">
                <label class="flex items-center gap-2 text-sm text-[#666] dark:text-[#aaa]">
                    <input type="checkbox" name="is_breaking" value="1" @checked($article->is_breaking) class="accent-[#E02020]"> Breaking
                </label>
                <label class="flex items-center gap-2 text-sm text-[#666] dark:text-[#aaa]">
                    <input type="checkbox" name="is_featured" value="1" @checked($article->is_featured) class="accent-[#0d0d0d]"> Featured
                </label>
                <label class="flex items-center gap-2 text-sm text-[#666] dark:text-[#aaa]">
                    <input type="checkbox" name="is_editor_pick" value="1" @checked($article->is_editor_pick) class="accent-[#0d0d0d]"> Editor's Pick
                </label>
            </div>
            <div class="flex gap-3 pt-4 border-t border-[#e0e0e0] dark:border-[#333]">
                <button type="submit" name="status" value="draft"
                    class="border border-[#0d0d0d] dark:border-white text-[#0d0d0d] dark:text-white px-6 py-2.5 text-sm font-medium hover:bg-[#f5f5f5] dark:hover:bg-[#333] transition">Draft</button>
                <button type="submit" name="status" value="published"
                    class="bg-[#E02020] text-white px-6 py-2.5 text-sm font-medium hover:bg-red-700 transition">Update &amp; Publish</button>
                <button type="submit" name="status" value="scheduled"
                    class="bg-blue-600 text-white px-6 py-2.5 text-sm font-medium hover:bg-blue-700 transition">Schedule</button>
            </div>
        </form>
    </div>

    {{-- SEO Analysis Panel --}}
    <div id="seoPanel" class="mt-6 bg-white dark:bg-[#1a1a1a] border border-[#e0e0e0] dark:border-[#333] p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <svg class="h-4 w-4 text-[#999]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <h2 class="font-bold">SEO Analysis</h2>
            </div>
            <div id="seoScore" class="text-2xl font-bold font-serif">—</div>
        </div>
        <div id="seoChecks" class="space-y-2"></div>
        <p class="text-xs text-[#999] mt-3">Auto-generated from the Post's Title, Body, and SEO fields</p>
    </div>


@endsection

@push('editor')
<x-head.editor-config/>
@endpush

@push('scripts')
<script>
async function fetchSeoAnalysis() {
    const articleId = {{ $article->id }};
    try {
        const res = await fetch('/admin/seo/article/' + articleId + '/analysis');
        const data = await res.json();
        const scoreEl = document.getElementById('seoScore');
        const checksEl = document.getElementById('seoChecks');
        scoreEl.textContent = data.score + '%';
        scoreEl.className = 'text-2xl font-bold font-serif ' + (data.score >= 80 ? 'text-green-600' : data.score >= 50 ? 'text-yellow-600' : 'text-red-600');
        checksEl.innerHTML = data.checks.map(c => {
            const icon = c.status === 'pass' ? '<svg class=\"h-4 w-4 text-green-600\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M5 13l4 4L19 7\"/></svg>' : c.status === 'warning' ? '<svg class=\"h-4 w-4 text-yellow-600\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M12 9v2m0 4h.01\"/></svg>' : '<svg class=\"h-4 w-4 text-red-600\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M6 18L18 6M6 6l12 12\"/></svg>';
            const color = c.status === 'pass' ? 'text-green-700 bg-green-50' : c.status === 'warning' ? 'text-yellow-700 bg-yellow-50' : 'text-red-700 bg-red-50';
            return '<div class="flex items-center gap-3 p-2 rounded ' + color + '">' + icon + '<div class="flex-1"><p class="text-sm font-medium">' + c.label + '</p><p class="text-xs opacity-80">' + c.message + '</p></div></div>';
        }).join('');
    } catch(e) {
        document.getElementById('seoChecks').innerHTML = '<p class="text-sm text-red-500">SEO analysis failed to load</p>';
    }
}
document.addEventListener('DOMContentLoaded', fetchSeoAnalysis);
</script>
@endpush
