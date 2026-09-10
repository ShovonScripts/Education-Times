@extends('layouts.admin')
@section('title', '301 Redirects Manager')
@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <div class="flex items-center gap-2">
            <svg class="h-5 w-5 text-[#999]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            <h1 class="text-2xl font-bold">301 Redirects Manager</h1>
        </div>
        <p class="text-xs text-[#999] mt-0.5">{{ $redirects->total() }} টি Redirects</p>
    </div>
    <a href="{{ route('admin.seo.dashboard') }}" class="border border-[#e0e0e0] dark:border-[#444] text-[#666] dark:text-[#aaa] px-4 py-2 text-xs font-medium hover:bg-[#f5f5f5] dark:hover:bg-[#2a2a2a] transition flex items-center gap-1">
        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        SEO Dashboard
    </a>
</div>

<div class="admin-card p-5 mb-6">
    <h2 class="text-sm font-bold mb-3">নতুন Redirects</h2>
    <form method="POST" action="{{ route('admin.seo.redirects.store') }}" class="flex flex-wrap items-end gap-3">
        @csrf
        <div class="flex-1 min-w-[180px]">
            <label class="block text-xs font-medium text-[#666] mb-1">পুরনো URL</label>
            <div class="flex items-center border border-[#e0e0e0] dark:border-[#444]">
                <span class="text-xs text-[#999] px-2">/</span>
                <input type="text" name="old_url" placeholder="old-page" required class="flex-1 p-2 text-sm border-0 focus:outline-none bg-transparent">
            </div>
        </div>
        <div class="flex-1 min-w-[180px]">
            <label class="block text-xs font-medium text-[#666] mb-1">নতুন URL</label>
            <input type="text" name="new_url" placeholder="/news/new-page" required class="admin-input w-full">
        </div>
        <div class="w-[100px]">
            <label class="block text-xs font-medium text-[#666] mb-1">টাইপ</label>
            <select name="status_code" class="admin-select w-full">
                <option value="301">301 (স্থায়ী)</option>
                <option value="302">302 (অস্থায়ী)</option>
            </select>
        </div>
        <button type="submit" class="btn-primary">Add</button>
    </form>
</div>

<div class="admin-card overflow-hidden">
    <table class="w-full text-sm">
        <thead class="admin-table-header">
            <tr>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">পুরনো URL</th>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider hidden md:table-cell">নতুন URL</th>
                <th class="text-center p-3 font-semibold text-[#666] text-xs uppercase tracking-wider w-[60px]">টাইপ</th>
                <th class="text-center p-3 font-semibold text-[#666] text-xs uppercase tracking-wider w-[60px] hidden sm:table-cell">হিট</th>
                <th class="text-center p-3 font-semibold text-[#666] text-xs uppercase tracking-wider w-[60px]">Status</th>
                <th class="text-right p-3 font-semibold text-[#666] text-xs uppercase tracking-wider w-[100px]">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#e0e0e0] dark:divide-[#333]">
            @forelse($redirects as $redirect)
            <tr class="admin-hover-row">
                <td class="p-3 font-mono text-xs">{{ $redirect->old_url }}</td>
                <td class="p-3 font-mono text-xs text-[#666] dark:text-[#aaa] hidden md:table-cell">{{ $redirect->new_url }}</td>
                <td class="p-3 text-center text-xs font-mono">{{ $redirect->status_code }}</td>
                <td class="p-3 text-center text-xs text-[#999] hidden sm:table-cell">{{ number_format($redirect->hits) }}</td>
                <td class="p-3 text-center">
                    <span class="text-xs px-2 py-0.5 {{ $redirect->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-[#666] dark:bg-gray-800 dark:text-[#aaa]' }}">{{ $redirect->is_active ? 'চালু' : 'বন্ধ' }}</span>
                </td>
                <td class="p-3 text-right">
                    <button type="button" onclick="editRedirect({{ $redirect->id }}, '{{ $redirect->old_url }}', '{{ $redirect->new_url }}', {{ $redirect->status_code }}, {{ $redirect->is_active ? 'true' : 'false' }})" class="text-[#666] hover:text-[#0d0d0d] text-xs mr-2">এডিট</button>
                    <form method="POST" action="{{ route('admin.seo.redirects.destroy', $redirect) }}" class="inline" onsubmit="return confirm('নিশ্চিত?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 text-xs">ডিলিট</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="p-8 text-center text-sm text-[#999]">কোনো Redirects নেই</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $redirects->links() }}</div>

<div id="editRedirectModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center hidden">
    <div class="admin-card w-full max-w-lg mx-4 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-bold">Redirects এডিট</h2>
            <button type="button" onclick="document.getElementById('editRedirectModal').classList.add('hidden')" class="text-[#999] hover:text-[#0d0d0d]">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" id="editRedirectForm">
            @csrf @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-[#666] mb-1">পুরনো URL</label>
                    <div class="flex items-center border border-[#e0e0e0] dark:border-[#444]">
                        <span class="text-xs text-[#999] px-2">/</span>
                        <input type="text" name="old_url" id="editOldUrl" required class="flex-1 p-2 text-sm border-0 focus:outline-none bg-transparent">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-[#666] mb-1">নতুন URL</label>
                    <input type="text" name="new_url" id="editNewUrl" required class="admin-input w-full">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-[#666] mb-1">টাইপ</label>
                        <select name="status_code" id="editStatusCode" class="admin-select w-full">
                            <option value="301">301 (স্থায়ী)</option>
                            <option value="302">302 (অস্থায়ী)</option>
                        </select>
                    </div>
                    <div>
                        <label class="flex items-center gap-2 text-sm mt-6">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" id="editIsActive" value="1" class="h-4 w-4 accent-[#0d0d0d]">
                            <span class="text-xs">Active</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="document.getElementById('editRedirectModal').classList.add('hidden')" class="btn-outline">Cancel</button>
                <button type="submit" class="btn-primary">আপডেট</button>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
function editRedirect(id, oldUrl, newUrl, code, active) {
    document.getElementById('editRedirectForm').action = '{{ route("admin.seo.redirects") }}/' + id;
    document.getElementById('editOldUrl').value = oldUrl.replace(/^\//, '');
    document.getElementById('editNewUrl').value = newUrl;
    document.getElementById('editStatusCode').value = code;
    document.getElementById('editIsActive').checked = active;
    document.getElementById('editRedirectModal').classList.remove('hidden');
}
</script>
@endpush
@endsection
