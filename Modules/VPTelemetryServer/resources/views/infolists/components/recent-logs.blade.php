<div>
    @php
        $logs = $getRecord()->logs()->latest()->take(10)->get();
    @endphp

    @if ($logs->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="text-left py-3 px-4 font-semibold">Event Type</th>
                        <th class="text-left py-3 px-4 font-semibold">Timestamp</th>
                        <th class="text-right py-3 px-4 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logs as $log)
                        <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset
                                    @if($log->event_type === 'install') bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 ring-green-700/10 dark:ring-green-300/20
                                    @elseif($log->event_type === 'update') bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 ring-blue-700/10 dark:ring-blue-300/20
                                    @elseif($log->event_type === 'heartbeat') bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 ring-purple-700/10 dark:ring-purple-300/20
                                    @else bg-gray-50 dark:bg-gray-900/30 text-gray-700 dark:text-gray-300 ring-gray-700/10 dark:ring-gray-300/20
                                    @endif">
                                    {{ ucfirst($log->event_type) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-gray-600 dark:text-gray-400">
                                {{ $log->created_at->format('Y-m-d H:i:s') }}
                                <span class="text-xs text-gray-500 dark:text-gray-500">
                                    ({{ $log->created_at->diffForHumans() }})
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <button 
                                    type="button"
                                    onclick="alert(JSON.stringify({{ json_encode($log->payload) }}, null, 2))"
                                    class="text-blue-600 dark:text-blue-400 hover:underline text-xs"
                                >
                                    View Payload
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="mt-2">No telemetry logs for this installation</p>
        </div>
    @endif
</div>
