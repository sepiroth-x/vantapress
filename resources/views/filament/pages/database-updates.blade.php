<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Status Card --}}
        <div class="rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Database Status
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        {{ $statusMessage }}
                    </p>
                </div>
                <div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $hasPendingMigrations ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' }}">
                        {{ $this->getStatusText() }}
                    </span>
                </div>
            </div>

            <div class="flex gap-3">
                <x-filament::button
                    wire:click="refreshStatus"
                    color="gray"
                    outlined
                >
                    Refresh Status
                </x-filament::button>

                @if($hasPendingMigrations || $hasFixScripts)
                    <x-filament::button
                        wire:click="runMigrations"
                        color="primary"
                        :disabled="$isRunning"
                    >
                        {{ $isRunning ? 'Running...' : 'Update Database Now' }}
                    </x-filament::button>
                @endif
            </div>
        </div>

        {{-- Fix Scripts Available --}}
        @if($hasFixScripts && count($availableFixScripts) > 0)
            <div class="rounded-lg border border-purple-300 dark:border-purple-700 bg-purple-50 dark:bg-purple-900/20 p-6">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <div class="flex-1">
                        <h3 class="text-base font-semibold text-purple-800 dark:text-purple-200 mb-2">
                            Automatic Fix Scripts Available
                        </h3>
                        <p class="text-sm text-purple-700 dark:text-purple-300 mb-3">
                            This update includes {{ $fixScriptCount }} automatic fix script(s) that will run when you click "Update Database Now". These scripts safely resolve database conflicts and perform maintenance tasks.
                        </p>
                        <ul class="space-y-2">
                            @foreach($availableFixScripts as $script)
                                <li class="flex items-start gap-2 text-sm text-purple-800 dark:text-purple-200">
                                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>{{ $this->formatMigrationName($script['name']) }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <p class="text-xs text-purple-600 dark:text-purple-400 mt-3">
                            ℹ️ Fix scripts check if they need to run and skip automatically if not applicable to your installation.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Pending Migrations --}}
        @if($hasPendingMigrations && count($pendingMigrations) > 0)
            <div class="rounded-lg border border-yellow-300 dark:border-yellow-700 bg-yellow-50 dark:bg-yellow-900/20 p-6">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div class="flex-1">
                        <h3 class="text-base font-semibold text-yellow-800 dark:text-yellow-200 mb-2">
                            Database Updates Required
                        </h3>
                        <p class="text-sm text-yellow-700 dark:text-yellow-300 mb-3">
                            Your VantaPress installation requires {{ count($pendingMigrations) }} database update(s) to function properly. Click "Update Database Now" to apply these changes.
                        </p>
                        <ul class="space-y-2">
                            @foreach($pendingMigrations as $migration)
                                <li class="flex items-start gap-2 text-sm text-yellow-800 dark:text-yellow-200">
                                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>{{ $this->formatMigrationName($migration) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- Module Migrations --}}
        @if($hasModuleMigrations && count($moduleMigrations) > 0)
            <div class="rounded-lg border border-indigo-300 dark:border-indigo-700 bg-indigo-50 dark:bg-indigo-900/20 p-6">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <div class="flex-1">
                        <h3 class="text-base font-semibold text-indigo-800 dark:text-indigo-200 mb-2">
                            Module Database Updates Required
                        </h3>
                        <p class="text-sm text-indigo-700 dark:text-indigo-300 mb-3">
                            {{ $totalModulePending }} module migration(s) are pending. These will run automatically when you activate the module, or you can run them now.
                        </p>
                        @foreach($moduleMigrations as $module)
                            <div class="mb-3 last:mb-0">
                                <h4 class="text-sm font-semibold text-indigo-800 dark:text-indigo-200 mb-1">
                                    📦 {{ $module['name'] }} ({{ $module['pending_count'] }} pending)
                                </h4>
                                <ul class="space-y-1 ml-4">
                                    @foreach($module['pending_migrations'] as $migration)
                                        <li class="flex items-start gap-2 text-sm text-indigo-700 dark:text-indigo-300">
                                            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                            <span>{{ $this->formatMigrationName($migration) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                        <p class="text-xs text-indigo-600 dark:text-indigo-400 mt-3">
                            ℹ️ Module migrations run automatically when you enable the module from the Modules page.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Theme Migrations --}}
        @if($hasThemeMigrations && count($themeMigrations) > 0)
            <div class="rounded-lg border border-pink-300 dark:border-pink-700 bg-pink-50 dark:bg-pink-900/20 p-6">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-pink-600 dark:text-pink-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                    </svg>
                    <div class="flex-1">
                        <h3 class="text-base font-semibold text-pink-800 dark:text-pink-200 mb-2">
                            Theme Database Updates Required
                        </h3>
                        <p class="text-sm text-pink-700 dark:text-pink-300 mb-3">
                            {{ $totalThemePending }} theme migration(s) are pending. These will run automatically when you activate the theme, or you can run them now.
                        </p>
                        @foreach($themeMigrations as $theme)
                            <div class="mb-3 last:mb-0">
                                <h4 class="text-sm font-semibold text-pink-800 dark:text-pink-200 mb-1">
                                    🎨 {{ $theme['name'] }} ({{ $theme['pending_count'] }} pending)
                                </h4>
                                <ul class="space-y-1 ml-4">
                                    @foreach($theme['pending_migrations'] as $migration)
                                        <li class="flex items-start gap-2 text-sm text-pink-700 dark:text-pink-300">
                                            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                            <span>{{ $this->formatMigrationName($migration) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                        <p class="text-xs text-pink-600 dark:text-pink-400 mt-3">
                            ℹ️ Theme migrations run automatically when you activate the theme from the Themes page.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Migration History --}}
        @if(count($migrationHistory) > 0)
            <div class="rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    Migration History
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    Recently executed database migrations (showing last {{ count($migrationHistory) }})
                </p>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-300 dark:border-gray-700">
                                <th class="text-left py-2 px-3 text-gray-700 dark:text-gray-300 font-semibold">Batch</th>
                                <th class="text-left py-2 px-3 text-gray-700 dark:text-gray-300 font-semibold">Migration</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($migrationHistory as $history)
                                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="py-2 px-3 text-gray-600 dark:text-gray-400">#{{ $history['batch'] }}</td>
                                    <td class="py-2 px-3 text-gray-900 dark:text-gray-100">
                                        {{ $this->formatMigrationName($history['migration']) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- Information Card --}}
        <div class="rounded-lg border border-blue-300 dark:border-blue-700 bg-blue-50 dark:bg-blue-900/20 p-6">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="flex-1">
                    <h3 class="text-base font-semibold text-blue-800 dark:text-blue-200 mb-2">
                        About Database Updates
                    </h3>
                    <div class="text-sm text-blue-700 dark:text-blue-300 space-y-2">
                        <p>
                            <strong>For Shared Hosting Users:</strong> This page allows you to run database migrations without terminal/SSH access. When you update VantaPress via FTP or file upload, new features may require database changes.
                        </p>
                        <p>
                            <strong>Safe & Automatic:</strong> VantaPress tracks which migrations have been applied. Running updates multiple times is safe - only new changes will be applied.
                        </p>
                        <p>
                            <strong>Auto-Updater Users:</strong> If you use the built-in auto-updater (System → Updates), migrations run automatically. This page is primarily for manual update methods.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Failed Scripts Alert --}}
        @if($hasFailedScripts && count($failedFixScripts) > 0)
            <div class="rounded-lg border-2 border-red-400 dark:border-red-600 bg-red-50 dark:bg-red-900/30 p-6">
                <div class="flex items-start gap-3">
                    <svg class="w-8 h-8 text-red-600 dark:text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-red-900 dark:text-red-200 mb-2">
                            ⚠️ Migration Fix Script{{ $failedScriptCount > 1 ? 's' : '' }} Failed
                        </h3>
                        <p class="text-sm text-red-800 dark:text-red-300 mb-4">
                            {{ $failedScriptCount }} migration fix script(s) encountered errors during execution. These scripts are designed to resolve database conflicts, but something went wrong.
                        </p>

                        {{-- Failed Scripts List --}}
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 mb-4">
                            <h4 class="font-semibold text-red-900 dark:text-red-200 mb-2">Failed Scripts:</h4>
                            <ul class="space-y-2">
                                @foreach($failedFixScripts as $script)
                                    <li class="flex items-start gap-2 text-sm text-gray-900 dark:text-gray-100">
                                        <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        <div>
                                            <code class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">{{ $script['name'] }}</code>
                                            <span class="text-xs text-gray-600 dark:text-gray-400 ml-2">Failed: {{ $script['modified_human'] }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- Troubleshooting Steps --}}
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 mb-4">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                                What This Means & How to Fix It
                            </h4>
                            <div class="text-sm text-gray-700 dark:text-gray-300 space-y-3">
                                <div>
                                    <strong class="text-gray-900 dark:text-gray-100">Why did this happen?</strong>
                                    <p class="mt-1">Migration fix scripts can fail for several reasons:</p>
                                    <ul class="list-disc ml-5 mt-1 space-y-1">
                                        <li>Database permissions issue (can't drop/create tables)</li>
                                        <li>A table the script expected to find doesn't exist</li>
                                        <li>Conflicting data that prevents cleanup</li>
                                        <li>Your database structure differs from what the script expects</li>
                                    </ul>
                                </div>

                                <div>
                                    <strong class="text-gray-900 dark:text-gray-100">What should you do?</strong>
                                    <ol class="list-decimal ml-5 mt-1 space-y-2">
                                        <li>
                                            <strong>Check the error logs:</strong>
                                            <ul class="list-disc ml-5 mt-1">
                                                <li>Go to <code class="text-xs bg-gray-100 dark:bg-gray-700 px-1 rounded">storage/logs/laravel.log</code></li>
                                                <li>Look for entries containing <code class="text-xs bg-gray-100 dark:bg-gray-700 px-1 rounded">[Migration Fixes]</code></li>
                                                <li>Note the specific error message (e.g., "table not found", "permission denied")</li>
                                            </ul>
                                        </li>
                                        <li>
                                            <strong>Try running the update again:</strong>
                                            <p class="mt-1">Sometimes temporary issues resolve themselves. Click "Refresh Status" then "Update Database Now" again.</p>
                                        </li>
                                        <li>
                                            <strong>Check database permissions:</strong>
                                            <p class="mt-1">Ensure your database user has permissions to CREATE, DROP, and ALTER tables.</p>
                                        </li>
                                        <li>
                                            <strong>Manual review may be needed:</strong>
                                            <p class="mt-1">Failed scripts are located at: <code class="text-xs bg-gray-100 dark:bg-gray-700 px-1 rounded">database/migration-fixes/failed/</code></p>
                                        </li>
                                    </ol>
                                </div>

                                <div class="border-t border-gray-300 dark:border-gray-600 pt-3">
                                    <strong class="text-gray-900 dark:text-gray-100">Can I continue using VantaPress?</strong>
                                    <p class="mt-1">
                                        <strong class="text-green-600 dark:text-green-400">Yes, usually!</strong> Failed fix scripts often address edge cases or clean up legacy data. Your site should continue working normally. However, you may encounter issues if:
                                    </p>
                                    <ul class="list-disc ml-5 mt-1">
                                        <li>The failed script was fixing a critical database conflict</li>
                                        <li>You're upgrading from a very old version</li>
                                        <li>Multiple scripts failed (indicates a larger issue)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Support Contact --}}
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-700">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Need Help? Contact VantaPress Support
                            </h4>
                            <div class="text-sm text-gray-700 dark:text-gray-300 space-y-2">
                                <p>If you can't resolve this issue, our support team is here to help!</p>
                                
                                <div class="bg-white dark:bg-gray-800 rounded p-3">
                                    <p class="font-semibold text-gray-900 dark:text-gray-100 mb-2">📧 Email: <a href="mailto:support@vantapress.com" class="text-blue-600 dark:text-blue-400 underline hover:text-blue-800 dark:hover:text-blue-300">support@vantapress.com</a></p>
                                    
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">Please include the following information in your email:</p>
                                    
                                    <div class="text-xs bg-gray-50 dark:bg-gray-900 rounded p-2 font-mono">
                                        <strong>Subject:</strong> Migration Fix Script Failed - [Script Name]<br><br>
                                        <strong>Include:</strong><br>
                                        1. VantaPress Version: {{ config('app.version', '1.1.9') }}<br>
                                        2. Failed Script(s): {{ implode(', ', array_column($failedFixScripts, 'name')) }}<br>
                                        3. Hosting Environment: (e.g., "Shared hosting - cPanel")<br>
                                        4. PHP Version: {{ PHP_VERSION }}<br>
                                        5. Database Type: {{ config('database.default', 'mysql') }}<br>
                                        6. Error from logs: (copy from storage/logs/laravel.log)<br>
                                        7. When did this occur: {{ now()->format('Y-m-d H:i:s') }}<br><br>
                                        <strong>Optional but helpful:</strong><br>
                                        - Screenshot of this page<br>
                                        - Last 50 lines of storage/logs/laravel.log<br>
                                        - Steps you took before the error occurred
                                    </div>
                                </div>

                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                    ⏱️ <strong>Response Time:</strong> We typically respond within 24 hours on business days. For urgent issues, include "URGENT" in your subject line.
                                </p>
                            </div>
                        </div>

                        {{-- Recent Errors --}}
                        @if($hasRecentErrors && count($recentErrors) > 0)
                            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 mt-4">
                                <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Recent Error Log Entries:</h4>
                                <div class="text-xs font-mono bg-gray-50 dark:bg-gray-900 rounded p-3 overflow-x-auto max-h-40 overflow-y-auto">
                                    @foreach($recentErrors as $error)
                                        <div class="text-red-600 dark:text-red-400 mb-1">{{ $error }}</div>
                                    @endforeach
                                </div>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-2">
                                    💡 Tip: Copy these errors when contacting support for faster resolution.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
