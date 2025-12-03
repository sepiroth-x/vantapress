<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Current Version Card --}}
        <x-filament::section>
            <x-slot name="heading">
                Current Version
            </x-slot>
            
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                        VantaPress v{{ $currentVersion }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Your current installation version
                    </p>
                </div>
                
                <div>
                    <x-filament::button
                        wire:click="checkForUpdates"
                        wire:loading.attr="disabled"
                        icon="heroicon-o-arrow-path"
                        color="gray"
                    >
                        <span wire:loading.remove wire:target="checkForUpdates">Check for Updates</span>
                        <span wire:loading wire:target="checkForUpdates">Checking...</span>
                    </x-filament::button>
                </div>
            </div>
        </x-filament::section>

        {{-- Update Status --}}
        @if($latestRelease)
            <x-filament::section>
                <x-slot name="heading">
                    @if($updateAvailable)
                        <div class="flex items-center space-x-2">
                            <span>🎉 Update Available</span>
                        </div>
                    @else
                        <div class="flex items-center space-x-2">
                            <span>✅ System Up to Date</span>
                        </div>
                    @endif
                </x-slot>
                
                <div class="space-y-4">
                    @if($updateAvailable)
                        <div class="rounded-lg bg-green-50 dark:bg-green-900/20 p-4 border border-green-200 dark:border-green-800">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <h3 class="text-lg font-medium text-green-800 dark:text-green-200">
                                        Version {{ $latestRelease['version'] }} Available
                                    </h3>
                                    <p class="mt-1 text-sm text-green-700 dark:text-green-300">
                                        Released {{ \Carbon\Carbon::parse($latestRelease['published_at'])->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="rounded-lg bg-blue-50 dark:bg-blue-900/20 p-4 border border-blue-200 dark:border-blue-800">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <h3 class="text-lg font-medium text-blue-800 dark:text-blue-200">
                                        You're Running the Latest Version
                                    </h3>
                                    <p class="mt-1 text-sm text-blue-700 dark:text-blue-300">
                                        VantaPress v{{ $currentVersion }} is up to date.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    {{-- Release Notes --}}
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Release Notes:</h4>
                        <div class="prose prose-sm dark:prose-invert max-w-none">
                            <div class="text-sm text-gray-600 dark:text-gray-400 whitespace-pre-wrap">
                                {{ $latestRelease['body'] }}
                            </div>
                        </div>
                    </div>
                    
                    {{-- Action Buttons --}}
                    <div class="flex space-x-3 mt-4">
                        <x-filament::button
                            wire:click="viewReleaseNotes"
                            icon="heroicon-o-document-text"
                            color="primary"
                            tag="a"
                            href="{{ $latestRelease['html_url'] }}"
                            target="_blank"
                        >
                            View Full Release Notes
                        </x-filament::button>
                        
                        @if($updateAvailable && $latestRelease['zipball_url'])
                            <x-filament::button
                                icon="heroicon-o-arrow-down-tray"
                                color="success"
                                tag="a"
                                href="{{ $latestRelease['zipball_url'] }}"
                                target="_blank"
                            >
                                Download Update
                            </x-filament::button>
                        @endif
                    </div>
                </div>
            </x-filament::section>
        @endif

        {{-- Update Instructions --}}
        <x-filament::section>
            <x-slot name="heading">
                Update Instructions
            </x-slot>
            
            <div class="prose prose-sm dark:prose-invert max-w-none">
                <ol class="space-y-3">
                    <li><strong>Backup your data:</strong> Always backup your database and files before updating.</li>
                    <li><strong>Download the update:</strong> Click "Download Update" button above or visit the GitHub releases page.</li>
                    <li><strong>Extract files:</strong> Extract the downloaded ZIP file to your VantaPress installation directory.</li>
                    <li><strong>Run migrations:</strong> Execute <code class="text-xs">php artisan migrate</code> in your terminal.</li>
                    <li><strong>Clear caches:</strong> Run <code class="text-xs">php artisan optimize:clear</code> to clear all caches.</li>
                    <li><strong>Test thoroughly:</strong> Verify that everything works correctly after the update.</li>
                </ol>
                
                <div class="mt-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                    <p class="text-sm text-yellow-800 dark:text-yellow-200">
                        <strong>⚠️ Important:</strong> Manual updates require technical knowledge. If you're not comfortable with the process, 
                        consider hiring a developer or waiting for automated update features in future releases.
                    </p>
                </div>
            </div>
        </x-filament::section>
        
        {{-- GitHub Repository Link --}}
        <x-filament::section>
            <x-slot name="heading">
                GitHub Repository
            </x-slot>
            
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        VantaPress is open source and hosted on GitHub. Star the repository, report issues, or contribute!
                    </p>
                </div>
                <x-filament::button
                    icon="heroicon-o-arrow-top-right-on-square"
                    color="gray"
                    tag="a"
                    href="https://github.com/sepiroth-x/vantapress"
                    target="_blank"
                >
                    View on GitHub
                </x-filament::button>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
