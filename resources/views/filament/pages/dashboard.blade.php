<x-filament-panels::page>
    <div class="grid gap-6 lg:gap-8">
        @php
            $activeTheme = config('cms.active_theme');
            $isVPSocialActive = $activeTheme === 'VPSocial';
        @endphp

        <!-- VPSocial Theme Indicator (only shown when VPSocial is active) -->
        @if($isVPSocialActive)
        <div class="fi-section rounded-xl bg-gradient-to-r from-purple-500 via-pink-500 to-red-500 shadow-lg ring-1 ring-purple-500/20">
            <div class="fi-section-content p-6 lg:p-8">
                <div class="flex items-center gap-4">
                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-white/20 backdrop-blur-sm">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-bold tracking-tight text-white">
                            🎉 VPSocial Theme Active!
                        </h2>
                        <p class="mt-1 text-sm text-white/90">
                            Your social network is live. Visit your newsfeed to see what's happening.
                        </p>
                        <div class="mt-3 flex gap-3">
                            <a href="/social/newsfeed" target="_blank" class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-purple-600 shadow-md transition-all hover:bg-white/90 hover:shadow-lg">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Visit Newsfeed
                            </a>
                            <a href="/social" target="_blank" class="inline-flex items-center gap-2 rounded-lg bg-white/10 px-4 py-2 text-sm font-semibold text-white backdrop-blur-sm transition-all hover:bg-white/20">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                Social Home
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Welcome Section -->
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="fi-section-content p-6 lg:p-8">
                <div class="flex items-center gap-4">
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold tracking-tight text-gray-950 dark:text-white">
                            Welcome back, {{ auth()->user()->name }}!
                        </h2>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Here's what's happening with your VantaPress site today.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4 lg:gap-6">
            <!-- Pages -->
            <div class="fi-stats-card rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-primary-100 dark:bg-primary-900/20">
                        <svg class="h-6 w-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Pages</p>
                        <p class="mt-1 text-2xl font-bold text-gray-950 dark:text-white">{{ \App\Models\Page::count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Media -->
            <div class="fi-stats-card rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-success-100 dark:bg-success-900/20">
                        <svg class="h-6 w-6 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Media Files</p>
                        <p class="mt-1 text-2xl font-bold text-gray-950 dark:text-white">{{ \App\Models\Media::count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Users -->
            <div class="fi-stats-card rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-info-100 dark:bg-info-900/20">
                        <svg class="h-6 w-6 text-info-600 dark:text-info-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Users</p>
                        <p class="mt-1 text-2xl font-bold text-gray-950 dark:text-white">{{ \App\Models\User::count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Modules -->
            <div class="fi-stats-card rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-warning-100 dark:bg-warning-900/20">
                        <svg class="h-6 w-6 text-warning-600 dark:text-warning-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Modules</p>
                        <p class="mt-1 text-2xl font-bold text-gray-950 dark:text-white">{{ \App\Models\Module::where('is_active', true)->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="fi-section-header flex items-center gap-x-3 overflow-hidden px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-base font-semibold leading-6 text-gray-950 dark:text-white">
                    Quick Actions
                </h3>
            </div>
            <div class="fi-section-content p-6">
                <div class="grid gap-4 md:grid-cols-3 lg:gap-6">
                    <a href="{{ route('filament.admin.resources.pages.create') }}" class="group flex items-center gap-3 rounded-lg border border-gray-200 bg-white p-4 transition-all hover:border-primary-500 hover:bg-primary-50 hover:shadow-md dark:border-gray-700 dark:bg-gray-800/50 dark:hover:border-primary-400 dark:hover:bg-primary-900/20">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary-100 transition-colors group-hover:bg-primary-200 dark:bg-primary-900/20 dark:group-hover:bg-primary-900/40">
                            <svg class="h-5 w-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-950 dark:text-white">New Page</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Create a new page</p>
                        </div>
                    </a>

                    <a href="{{ route('filament.admin.resources.media.create') }}" class="group flex items-center gap-3 rounded-lg border border-gray-200 bg-white p-4 transition-all hover:border-success-500 hover:bg-success-50 hover:shadow-md dark:border-gray-700 dark:bg-gray-800/50 dark:hover:border-success-400 dark:hover:bg-success-900/20">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-success-100 transition-colors group-hover:bg-success-200 dark:bg-success-900/20 dark:group-hover:bg-success-900/40">
                            <svg class="h-5 w-5 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-950 dark:text-white">Upload Media</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Add new media files</p>
                        </div>
                    </a>

                    <a href="{{ route('filament.admin.pages.settings') }}" class="group flex items-center gap-3 rounded-lg border border-gray-200 bg-white p-4 transition-all hover:border-info-500 hover:bg-info-50 hover:shadow-md dark:border-gray-700 dark:bg-gray-800/50 dark:hover:border-info-400 dark:hover:bg-info-900/20">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-info-100 transition-colors group-hover:bg-info-200 dark:bg-info-900/20 dark:group-hover:bg-info-900/40">
                            <svg class="h-5 w-5 text-info-600 dark:text-info-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-950 dark:text-white">Settings</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Configure your site</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
