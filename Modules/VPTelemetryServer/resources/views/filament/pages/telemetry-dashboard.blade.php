<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Header Widgets (Stats Overview) -->
        <div>
            @foreach ($this->getHeaderWidgets() as $widget)
                @livewire($widget)
            @endforeach
        </div>

        <!-- Main Widgets (Charts) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach ($this->getWidgets() as $widget)
                <div class="filament-widget">
                    @livewire($widget)
                </div>
            @endforeach
        </div>

        <!-- Quick Info Section -->
        <x-filament::section>
            <x-slot name="heading">
                About Telemetry Data
            </x-slot>
            <x-slot name="description">
                Understanding the data collected from VantaPress installations
            </x-slot>

            <div class="prose dark:prose-invert max-w-none text-sm">
                <p>
                    This dashboard displays <strong>anonymous usage statistics</strong> from VantaPress installations 
                    that have telemetry enabled. No personal data, content, or sensitive information is collected.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                        <h4 class="font-semibold text-blue-900 dark:text-blue-300 mb-2">📊 Statistics</h4>
                        <p class="text-blue-800 dark:text-blue-400 text-xs">
                            Track total installations, active users, version adoption, and module/theme popularity.
                        </p>
                    </div>

                    <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                        <h4 class="font-semibold text-green-900 dark:text-green-300 mb-2">🔒 Privacy First</h4>
                        <p class="text-green-800 dark:text-green-400 text-xs">
                            Only system information is collected. No emails, passwords, content, or user data.
                        </p>
                    </div>

                    <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg">
                        <h4 class="font-semibold text-purple-900 dark:text-purple-300 mb-2">🎯 Purpose</h4>
                        <p class="text-purple-800 dark:text-purple-400 text-xs">
                            Improve VantaPress by understanding real-world usage patterns and prioritizing features.
                        </p>
                    </div>
                </div>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
