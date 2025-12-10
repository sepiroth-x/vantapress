<div class="space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <h4 class="text-sm font-semibold mb-2">Event Type</h4>
            <span class="inline-flex items-center rounded-md bg-blue-50 dark:bg-blue-900/30 px-3 py-1.5 text-sm font-medium text-blue-700 dark:text-blue-300 ring-1 ring-inset ring-blue-700/10 dark:ring-blue-300/20">
                {{ $log['event_type'] }}
            </span>
        </div>
        <div>
            <h4 class="text-sm font-semibold mb-2">Sent At</h4>
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $log['sent_at'] }}</p>
        </div>
    </div>

    <div>
        <h4 class="text-sm font-semibold mb-2">Payload</h4>
        <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-4 text-xs font-mono overflow-x-auto">
            <pre class="text-gray-800 dark:text-gray-200">{{ json_encode($log['payload'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
        </div>
    </div>
</div>
