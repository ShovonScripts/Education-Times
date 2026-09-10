@extends('layouts.admin')
@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="font-serif text-2xl font-bold">Users ব্যবস্থাপNo</h1>
        <p class="text-xs text-[#999] mt-0.5">মোট {{ $totalUsers }} জন Users</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="bg-[#0d0d0d] dark:bg-[#333] text-white px-5 py-2 text-sm font-medium hover:bg-black dark:hover:bg-[#444] transition">+ নতুন Users</a>
</div>

{{-- Stats --}}
<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="bg-white border border-[#e0e0e0] p-4">
        <p class="text-2xl font-bold font-serif">{{ $totalUsers }}</p>
        <p class="text-xs text-[#999]">মোট</p>
    </div>
    <div class="bg-white border border-[#e0e0e0] p-4">
        <p class="text-2xl font-bold font-serif text-[#E02020]">{{ $adminCount }}</p>
        <p class="text-xs text-[#999]">Admins</p>
    </div>
    <div class="bg-white border border-[#e0e0e0] p-4">
        <p class="text-2xl font-bold font-serif text-blue-600">{{ $editorCount }}</p>
        <p class="text-xs text-[#999]">এডিটর</p>
    </div>
    <div class="bg-white border border-[#e0e0e0] p-4">
        <p class="text-2xl font-bold font-serif text-green-600">{{ $activeCount }}</p>
        <p class="text-xs text-[#999]">Active</p>
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap items-center gap-2 mb-5">
    <input type="text" name="search" placeholder="Name/Email/ফোন..." value="{{ request('search') }}" class="border border-[#e0e0e0] px-3 py-2 text-sm min-w-[180px]">
    <select name="role" class="border border-[#e0e0e0] px-3 py-2 text-sm bg-white">
        <option value="">সব রোল</option>
        <option value="admin" @selected(request('role') === 'admin')>Admins</option>
        <option value="editor" @selected(request('role') === 'editor')>এডিটর</option>
        <option value="user" @selected(request('role') === 'user')>Users</option>
    </select>
    <select name="status" class="border border-[#e0e0e0] px-3 py-2 text-sm bg-white">
        <option value="">সব Status</option>
        <option value="active" @selected(request('status') === 'active')>Active</option>
        <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
    </select>
    <button type="submit" class="bg-[#0d0d0d] dark:bg-[#333] text-white px-4 py-2 text-sm">ফিল্টার</button>
    <a href="{{ route('admin.users.index') }}" class="text-xs text-[#999] hover:text-[#E02020]">রিসেট</a>
</form>

{{-- Table --}}
<div class="bg-white border border-[#e0e0e0] overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-[#f5f5f5] border-b border-[#e0e0e0]">
            <tr>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Users</th>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider hidden md:table-cell">Email</th>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider hidden sm:table-cell">ফোন</th>
                <th class="text-left p-3 font-semibold text-[#666] text-xs uppercase tracking-wider hidden lg:table-cell">জেলা</th>
                <th class="text-center p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">রোল</th>
                <th class="text-center p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Status</th>
                <th class="text-center p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">যোগদান</th>
                <th class="text-right p-3 font-semibold text-[#666] text-xs uppercase tracking-wider">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#e0e0e0]">
            @forelse($users as $user)
            <tr class="hover:bg-[#fafafa] {{ !$user->is_active ? 'opacity-60' : '' }}">
                <td class="p-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-[#0d0d0d] dark:bg-[#333] text-white flex items-center justify-center text-xs font-bold shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-sm">{{ $user->name }}</p>
                            <p class="text-[10px] text-[#999]">{{ $user->designation ?? '—' }}</p>
                        </div>
                    </div>
                </td>
                <td class="p-3 text-xs text-[#666] hidden md:table-cell">{{ $user->email }}</td>
                <td class="p-3 text-xs text-[#666] hidden sm:table-cell">{{ $user->phone ?? '—' }}</td>
                <td class="p-3 text-xs text-[#666] hidden lg:table-cell">{{ $user->district?->name_bn ?? '—' }}</td>
                <td class="p-3 text-center">
                    @if($user->is_admin)
                        <span class="text-[10px] font-semibold bg-[#E02020]/10 text-[#E02020] px-2 py-0.5">Admins</span>
                    @elseif($user->is_editor)
                        <span class="text-[10px] font-semibold bg-blue-100 text-blue-700 px-2 py-0.5">এডিটর</span>
                    @else
                        <span class="text-[10px] font-semibold bg-gray-100 text-[#666] px-2 py-0.5">Users</span>
                    @endif
                </td>
                <td class="p-3 text-center">
                    @if($user->is_active)
                        <span class="text-[10px] text-green-600 font-semibold">Active</span>
                    @else
                        <span class="text-[10px] text-red-500 font-semibold">Inactive</span>
                    @endif
                </td>
                <td class="p-3 text-xs text-[#999] text-center">{{ $user->created_at->format('d/m/Y') }}</td>
                <td class="p-3 text-right">
                    <div class="flex items-center justify-end gap-1">
                        <a href="{{ route('admin.users.edit', $user) }}" class="text-[#666] hover:text-[#0d0d0d] text-xs px-2 py-1 hover:bg-[#f5f5f5] rounded">এডিট</a>
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}" class="inline">
                            @csrf
                            <button type="submit" class="text-xs px-2 py-1 rounded hover:bg-[#f5f5f5] {{ $user->is_active ? 'text-red-500 hover:text-red-700' : 'text-green-600 hover:text-green-700' }}">
                                {{ $user->is_active ? 'Inactive' : 'Active' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('{{ $user->name }} কে ডিলিট করবেন?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs px-2 py-1 rounded hover:bg-red-50">ডিলিট</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="p-8 text-center text-sm text-[#999]">কোনো Users পাওয়া যায়নি</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $users->links() }}</div>
@endsection
