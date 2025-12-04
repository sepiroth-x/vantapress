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
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Release Notes:
                        </h4>
                        <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4 bg-white dark:bg-gray-800">
                            <div class="prose prose-sm dark:prose-invert max-w-none
                                prose-headings:text-gray-900 dark:prose-headings:text-white
                                prose-h1:text-2xl prose-h1:font-bold prose-h1:mb-4 prose-h1:border-b prose-h1:border-gray-200 dark:prose-h1:border-gray-700 prose-h1:pb-2
                                prose-h2:text-xl prose-h2:font-semibold prose-h2:mt-6 prose-h2:mb-3
                                prose-h3:text-lg prose-h3:font-medium prose-h3:mt-4 prose-h3:mb-2
                                prose-p:text-gray-700 dark:prose-p:text-gray-300 prose-p:leading-relaxed
                                prose-a:text-blue-600 dark:prose-a:text-blue-400 prose-a:no-underline hover:prose-a:underline
                                prose-strong:text-gray-900 dark:prose-strong:text-white prose-strong:font-semibold
                                prose-em:text-gray-700 dark:prose-em:text-gray-300
                                prose-code:text-pink-600 dark:prose-code:text-pink-400 prose-code:bg-gray-100 dark:prose-code:bg-gray-900 prose-code:px-1.5 prose-code:py-0.5 prose-code:rounded prose-code:text-sm prose-code:before:content-[''] prose-code:after:content-['']
                                prose-pre:bg-gray-900 dark:prose-pre:bg-gray-950 prose-pre:text-gray-100 prose-pre:p-4 prose-pre:rounded-lg prose-pre:overflow-x-auto
                                prose-blockquote:border-l-4 prose-blockquote:border-blue-500 prose-blockquote:pl-4 prose-blockquote:italic prose-blockquote:text-gray-600 dark:prose-blockquote:text-gray-400
                                prose-ul:list-disc prose-ul:pl-6 prose-ul:space-y-2
                                prose-ol:list-decimal prose-ol:pl-6 prose-ol:space-y-2
                                prose-li:text-gray-700 dark:prose-li:text-gray-300
                                prose-hr:border-gray-300 dark:prose-hr:border-gray-700 prose-hr:my-6
                                prose-table:border-collapse prose-table:w-full
                                prose-th:bg-gray-100 dark:prose-th:bg-gray-800 prose-th:p-2 prose-th:text-left prose-th:font-semibold prose-th:border prose-th:border-gray-300 dark:prose-th:border-gray-700
                                prose-td:p-2 prose-td:border prose-td:border-gray-300 dark:prose-td:border-gray-700
                                prose-img:rounded-lg prose-img:shadow-md">
                                {!! \Illuminate\Mail\Markdown::parse($latestRelease['body']) !!}
                            </div>
                        </div>
                    </div>
                    
                    {{-- Action Buttons --}}
                    <div class="flex space-x-3 mt-4">
                        @if($updateAvailable)
                            <x-filament::button
                                wire:click="installUpdate"
                                wire:loading.attr="disabled"
                                icon="heroicon-o-arrow-down-circle"
                                color="success"
                                size="lg"
                            >
                                <span wire:loading.remove wire:target="installUpdate">
                                    🚀 Install Update Automatically
                                </span>
                                <span wire:loading wire:target="installUpdate">
                                    ⏳ Installing Update...
                                </span>
                            </x-filament::button>
                        @endif
                        
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
                                color="gray"
                                tag="a"
                                href="{{ $latestRelease['zipball_url'] }}"
                                target="_blank"
                            >
                                Download Manually
                            </x-filament::button>
                        @endif
                    </div>
                    
                    {{-- Update Progress --}}
                    @if($updateProgress)
                        <div class="mt-6 space-y-3">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">Update Progress:</h4>
                            
                            @foreach($updateProgress['steps'] as $index => $step)
                                <div class="flex items-start space-x-3 p-3 rounded-lg {{ $step['success'] ? 'bg-green-50 dark:bg-green-900/20' : 'bg-red-50 dark:bg-red-900/20' }} border {{ $step['success'] ? 'border-green-200 dark:border-green-800' : 'border-red-200 dark:border-red-800' }}">
                                    <span class="text-lg">
                                        @if($step['success'])
                                            ✅
                                        @else
                                            ❌
                                        @endif
                                    </span>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium {{ $step['success'] ? 'text-green-800 dark:text-green-200' : 'text-red-800 dark:text-red-200' }}">
                                            Step {{ $index + 1 }}: {{ $step['message'] }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                            
                            @if($updateProgress['success'])
                                <div class="p-4 rounded-lg bg-green-100 dark:bg-green-900/30 border-2 border-green-500">
                                    <p class="text-green-800 dark:text-green-200 font-bold text-center">
                                        🎉 Update completed successfully! Refreshing page...
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </x-filament::section>
        @endif

        {{-- Update Instructions --}}
        <x-filament::section>
            <x-slot name="heading">
                How Updates Work
            </x-slot>
            
            <div class="prose prose-sm dark:prose-invert max-w-none">
                <h4 class="text-lg font-semibold mb-3">✨ Automatic Updates (Recommended)</h4>
                <p class="mb-4">
                    VantaPress now features WordPress-style automatic updates! Just click the 
                    <strong class="text-green-600">"Install Update Automatically"</strong> button and the system will:
                </p>
                <ol class="space-y-2 mb-6">
                    <li>🔒 <strong>Create a backup</strong> of your current installation</li>
                    <li>📥 <strong>Download</strong> the latest version from GitHub</li>
                    <li>📦 <strong>Extract</strong> the update package</li>
                    <li>🔄 <strong>Apply updates</strong> while protecting your .env and storage files</li>
                    <li>🗄️ <strong>Run migrations</strong> automatically</li>
                    <li>🧹 <strong>Clear caches</strong> for clean operation</li>
                    <li>✅ <strong>Verify</strong> and refresh the admin panel</li>
                </ol>
                
                <div class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg mb-6">
                    <p class="text-sm text-green-800 dark:text-green-200">
                        <strong>💡 Pro Tip:</strong> The automatic update system is safe and includes automatic rollback 
                        if something goes wrong. Your .env file and uploaded files in storage/ are always protected.
                    </p>
                </div>
                
                <h4 class="text-lg font-semibold mb-3">📝 Manual Update (Alternative)</h4>
                <p class="mb-2">If you prefer manual updates or automatic update fails:</p>
                <ol class="space-y-3">
                    <li><strong>Backup your data:</strong> Download your database and files.</li>
                    <li><strong>Download update:</strong> Click "Download Manually" button.</li>
                    <li><strong>Extract files:</strong> Upload extracted files via FTP to your server.</li>
                    <li><strong>Run migrations:</strong> Execute <code class="text-xs">php artisan migrate --force</code></li>
                    <li><strong>Clear caches:</strong> Run <code class="text-xs">php artisan optimize:clear</code></li>
                </ol>
                
                <div class="mt-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                    <p class="text-sm text-yellow-800 dark:text-yellow-200">
                        <strong>⚠️ Important:</strong> Always ensure your .env file is backed up and never overwritten 
                        during updates. The automatic updater handles this for you!
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
    
    {{-- Auto-refresh script --}}
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('refresh-page', (event) => {
                const delay = event.delay || 3000;
                setTimeout(() => {
                    window.location.reload();
                }, delay);
            });
        });
    </script>
</x-filament-panels::page>
