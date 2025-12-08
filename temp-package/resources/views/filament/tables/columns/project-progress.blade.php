<div class="flex items-center gap-2">
    <div class="flex-1">
        <div class="h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
            <div 
                class="h-full transition-all duration-300"
                style="width: {{ $getRecord()->progress }}%; background: {{ $getRecord()->color }};"
            ></div>
        </div>
    </div>
    <span class="text-xs font-medium text-gray-600 dark:text-gray-400 whitespace-nowrap">
        {{ number_format($getRecord()->progress, 0) }}%
    </span>
</div>
