<x-filament-panels::page>
    <div class="space-y-6">
        {{ $this->form }}

        @if ($telemetryEnabled)
            <x-filament::section>
                <x-slot name="heading">
                    Recent Telemetry Logs
                </x-slot>
                <x-slot name="description">
                    View recent data transmissions
                </x-slot>

                <div class="overflow-x-auto">
                    @php
                        $logs = \Modules\VPTelemetry\Models\TelemetryLog::latest()->take(10)->get();
                    @endphp

                    @if ($logs->count() > 0)
                        <table class="w-full text-sm">
                            <thead class="border-b dark:border-gray-700">
                                <tr>
                                    <th class="text-left py-2 px-4">Event Type</th>
                                    <th class="text-left py-2 px-4">Sent At</th>
                                    <th class="text-left py-2 px-4">Status</th>
                                    <th class="text-right py-2 px-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logs as $log)
                                    <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                                        <td class="py-2 px-4">
                                            <span class="inline-flex items-center rounded-md bg-blue-50 dark:bg-blue-900/30 px-2 py-1 text-xs font-medium text-blue-700 dark:text-blue-300 ring-1 ring-inset ring-blue-700/10 dark:ring-blue-300/20">
                                                {{ $log->event_type }}
                                            </span>
                                        </td>
                                        <td class="py-2 px-4 text-gray-600 dark:text-gray-400">
                                            {{ $log->sent_at ? $log->sent_at->format('Y-m-d H:i:s') : 'Not sent' }}
                                        </td>
                                        <td class="py-2 px-4">
                                            @if ($log->sent_at)
                                                <span class="inline-flex items-center rounded-md bg-green-50 dark:bg-green-900/30 px-2 py-1 text-xs font-medium text-green-700 dark:text-green-300">
                                                    Sent
                                                </span>
                                            @else
                                                <span class="inline-flex items-center rounded-md bg-yellow-50 dark:bg-yellow-900/30 px-2 py-1 text-xs font-medium text-yellow-700 dark:text-yellow-300">
                                                    Pending
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-2 px-4 text-right">
                                            <button 
                                                type="button"
                                                onclick="alert('{{ json_encode($log->payload, JSON_PRETTY_PRINT) }}')"
                                                class="text-blue-600 dark:text-blue-400 hover:underline text-xs"
                                            >
                                                View Payload
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p class="mt-2">No telemetry logs yet</p>
                        </div>
                    @endif
                </div>
            </x-filament::section>
        @endif
    </div>
</x-filament-panels::page>
