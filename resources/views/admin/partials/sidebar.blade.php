<aside id="adminSidebar" class="w-56 bg-[#0d0d0d] dark:bg-[#0a0a0a] text-white hidden md:flex flex-col shrink-0 overflow-y-auto border-r border-white/5">
    <div class="h-14 flex items-center px-5 border-b border-white/10 shrink-0">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 shrink-0">
            @if($siteLogo = \App\Models\Setting::get('site_logo'))
                <img src="{{ Storage::url($siteLogo) }}" alt="{{ \App\Models\Setting::get('site_name_bn', config('app.name')) }}" class="h-8 w-auto">
            @else
                <span class="font-serif font-bold text-lg tracking-tight text-white">ET <span class="text-[#E02020] font-bold">Admin</span></span>
            @endif
        </a>
    </div>
    <nav class="flex-1 py-5 px-3 space-y-0.5 text-sm">

        <a href="{{ route('admin.dashboard') }}" class="sidebar-link @if(request()->routeIs('admin.dashboard')) active @endif">
            <svg class="sidebar-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span>Dashboard</span>
        </a>

        <div class="section-header">Posts</div>

        <a href="{{ route('admin.posts.index') }}" class="sidebar-link @if(request()->routeIs('admin.posts.index') && !request('status')) active @endif">
            <svg class="sidebar-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            <span>All Posts</span>
        </a>

        <a href="{{ route('admin.posts.pending') }}" class="sidebar-link @if(request()->routeIs('admin.posts.pending')) active @endif">
            <svg class="sidebar-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Pending Review</span>
            @if($pendingCount > 0)
            <span class="badge-sidebar badge-submitted">{{ $pendingCount }}</span>
            @endif
        </a>

        <a href="{{ route('admin.posts.slider') }}" class="sidebar-link @if(request()->routeIs('admin.posts.slider')) active @endif">
            <svg class="sidebar-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            <span>Slider</span>
        </a>

        <a href="{{ route('admin.posts.breaking') }}" class="sidebar-link @if(request()->routeIs('admin.posts.breaking')) active @endif">
            <svg class="sidebar-icon text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            <span>Breaking News</span>
        </a>

        <a href="{{ route('admin.posts.featured') }}" class="sidebar-link @if(request()->routeIs('admin.posts.featured')) active @endif">
            <svg class="sidebar-icon text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            <span>Featured</span>
        </a>

        <a href="{{ route('admin.posts.scheduled') }}" class="sidebar-link @if(request()->routeIs('admin.posts.scheduled')) active @endif">
            <svg class="sidebar-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span>Scheduled</span>
            @if($scheduledCount > 0)
            <span class="badge-sidebar badge-scheduled">{{ $scheduledCount }}</span>
            @endif
        </a>

        <a href="{{ route('admin.articles.create') }}" class="sidebar-link-new">
            <svg class="sidebar-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>New Post</span>
        </a>

        <div class="section-header">Manage</div>

        <a href="{{ route('admin.categories.index') }}" class="sidebar-link @if(request()->routeIs('admin.categories.*')) active @endif">
            <svg class="sidebar-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            <span>Categories</span>
        </a>

        <a href="{{ route('admin.comments.index') }}" class="sidebar-link @if(request()->routeIs('admin.comments.*')) active @endif">
            <svg class="sidebar-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            <span>Comments</span>
        </a>

        <a href="{{ route('admin.contacts.index') }}" class="sidebar-link @if(request()->routeIs('admin.contacts.*')) active @endif">
            <svg class="sidebar-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <span>Messages</span>
            @if($unreadContactCount > 0)
            <span class="badge-sidebar badge-submitted">{{ $unreadContactCount }}</span>
            @endif
        </a>

        <div class="section-header">People</div>

        <a href="{{ route('admin.staff.index') }}" class="sidebar-link @if(request()->routeIs('admin.staff.*')) active @endif">
            <svg class="sidebar-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <span>Staff</span>
        </a>

        <a href="{{ route('admin.users.index') }}" class="sidebar-link @if(request()->routeIs('admin.users.*') && !request('role')) active @endif">
            <svg class="sidebar-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            <span>Users</span>
        </a>

        <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" class="sidebar-link @if(request()->routeIs('admin.users.*') && request('role') === 'admin') active @endif">
            <svg class="sidebar-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            <span>Admins</span>
            <span class="badge-sidebar badge-admin">{{ $adminCount }}</span>
        </a>

        <div class="section-header">SEO</div>

        <a href="{{ route('admin.seo.dashboard') }}" class="sidebar-link @if(request()->routeIs('admin.seo.dashboard')) active @endif">
            <svg class="sidebar-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 3h6v6M9 21H3v-6"/></svg>
            <span>SEO Dashboard</span>
        </a>

        <a href="{{ route('admin.seo.bulk-editor') }}" class="sidebar-link @if(request()->routeIs('admin.seo.bulk-editor')) active @endif">
            <svg class="sidebar-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            <span>Bulk SEO</span>
        </a>

        <a href="{{ route('admin.seo.redirects') }}" class="sidebar-link @if(request()->routeIs('admin.seo.redirects*')) active @endif">
            <svg class="sidebar-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
            <span>Redirects</span>
        </a>

        <a href="{{ route('admin.seo.robots') }}" class="sidebar-link @if(request()->routeIs('admin.seo.robots')) active @endif">
            <svg class="sidebar-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <span>robots.txt</span>
        </a>

        <div class="section-header">Settings</div>

        <a href="{{ route('admin.settings.index') }}" class="sidebar-link @if(request()->routeIs('admin.settings.*')) active @endif">
            <svg class="sidebar-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <span>Settings</span>
        </a>

        <a href="{{ route('admin.ads.index') }}" class="sidebar-link @if(request()->routeIs('admin.ads.*')) active @endif">
            <svg class="sidebar-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
            <span>Advertisements</span>
        </a>

        <a href="{{ route('admin.partners.index') }}" class="sidebar-link @if(request()->routeIs('admin.partners.*')) active @endif">
            <svg class="sidebar-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <span>Partners</span>
        </a>

        <a href="{{ route('admin.newsletter.index') }}" class="sidebar-link @if(request()->routeIs('admin.newsletter.*')) active @endif">
            <svg class="sidebar-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <span>Newsletter</span>
            @if($subscriberCount > 0)
            <span class="badge-sidebar badge-admin">{{ $subscriberCount }}</span>
            @endif
        </a>

        <div class="section-header">Pages</div>

        <a href="{{ route('admin.pages.index') }}" class="sidebar-link @if(request()->routeIs('admin.pages.*')) active @endif">
            <svg class="sidebar-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span>Pages</span>
        </a>

        <a href="{{ route('admin.archive.index') }}" class="sidebar-link @if(request()->routeIs('admin.archive.*')) active @endif">
            <svg class="sidebar-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            <span>Archives</span>
        </a>

    </nav>
</aside>
