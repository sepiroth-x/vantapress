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
    </div>
</x-filament-panels::page>
