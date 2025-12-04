<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-[calc(100vh-200px)]">
        <!-- Customizer Panel (Left Side) -->
        <div class="lg:col-span-1 overflow-y-auto pr-4 customizer-panel">
            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold mb-2">Theme: {{ $record->name }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $record->description }}</p>
                    @if($record->is_active)
                        <div class="mt-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Active Theme
                            </span>
                        </div>
                    @endif
                </div>
                
                {{ $this->form }}
            </div>
        </div>
        
        <!-- Live Preview (Right Side) -->
        <div class="lg:col-span-2 bg-gray-100 dark:bg-gray-900 rounded-lg overflow-hidden preview-container">
            <div class="bg-white dark:bg-gray-800 px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Live Preview</span>
                </div>
                
                <div class="flex items-center space-x-2">
                    <!-- Desktop View -->
                    <button 
                        type="button"
                        onclick="setPreviewMode('desktop')"
                        class="preview-mode-btn active px-3 py-1 text-xs rounded bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600"
                        data-mode="desktop">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                    
                    <!-- Tablet View -->
                    <button 
                        type="button"
                        onclick="setPreviewMode('tablet')"
                        class="preview-mode-btn px-3 py-1 text-xs rounded bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600"
                        data-mode="tablet">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M7 2a2 2 0 00-2 2v12a2 2 0 002 2h6a2 2 0 002-2V4a2 2 0 00-2-2H7zm3 14a1 1 0 100-2 1 1 0 000 2z"/>
                        </svg>
                    </button>
                    
                    <!-- Mobile View -->
                    <button 
                        type="button"
                        onclick="setPreviewMode('mobile')"
                        class="preview-mode-btn px-3 py-1 text-xs rounded bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600"
                        data-mode="mobile">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M7 2a2 2 0 00-2 2v12a2 2 0 002 2h6a2 2 0 002-2V4a2 2 0 00-2-2H7zm3 14a1 1 0 100-2 1 1 0 000 2z"/>
                        </svg>
                    </button>
                    
                    <!-- Refresh Button -->
                    <button 
                        type="button"
                        onclick="document.getElementById('preview-frame').contentWindow.location.reload()"
                        class="px-3 py-1 text-xs rounded bg-primary-600 text-white hover:bg-primary-700">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="h-[calc(100%-56px)] bg-gray-200 dark:bg-gray-900 flex items-center justify-center preview-wrapper">
                <div id="preview-container" class="preview-desktop bg-white shadow-2xl h-full w-full overflow-hidden transition-all duration-300">
                    <iframe 
                        id="preview-frame"
                        src="{{ $previewUrl }}"
                        class="w-full h-full border-0"
                        sandbox="allow-same-origin allow-scripts allow-popups allow-forms"
                    ></iframe>
                </div>
            </div>
        </div>
    </div>
    
    <style>
        .customizer-panel {
            max-height: calc(100vh - 200px);
        }
        
        .preview-container {
            position: relative;
        }
        
        .preview-wrapper {
            padding: 20px;
        }
        
        .preview-desktop {
            width: 100%;
            max-width: 100%;
        }
        
        .preview-tablet {
            width: 768px;
            max-width: 90%;
            height: 90%;
        }
        
        .preview-mobile {
            width: 375px;
            max-width: 90%;
            height: 90%;
        }
        
        .preview-mode-btn.active {
            background-color: rgb(var(--primary-600));
            color: white;
        }
        
        .dark .preview-mode-btn.active {
            background-color: rgb(var(--primary-600));
        }
    </style>
    
    <script>
        function setPreviewMode(mode) {
            const container = document.getElementById('preview-container');
            const buttons = document.querySelectorAll('.preview-mode-btn');
            
            // Remove all mode classes
            container.classList.remove('preview-desktop', 'preview-tablet', 'preview-mobile');
            
            // Add new mode class
            container.classList.add('preview-' + mode);
            
            // Update button states
            buttons.forEach(btn => {
                if (btn.dataset.mode === mode) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        }
        
        // Listen for refresh events from form
        window.addEventListener('refresh-preview', () => {
            const iframe = document.getElementById('preview-frame');
            if (iframe) {
                iframe.contentWindow.location.reload();
            }
        });
        
        // Livewire hook to refresh preview
        Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
            succeed(({ snapshot, effect }) => {
                if (component.name === 'filament.resources.theme-resource.pages.customize-theme') {
                    setTimeout(() => {
                        const iframe = document.getElementById('preview-frame');
                        if (iframe) {
                            iframe.contentWindow.location.reload();
                        }
                    }, 500);
                }
            });
        });
    </script>
</x-filament-panels::page>
