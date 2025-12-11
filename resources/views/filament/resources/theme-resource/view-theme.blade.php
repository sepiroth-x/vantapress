<div class="space-y-4">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Version</h4>
            <p class="text-base">{{ $record->version }}</p>
        </div>
        
        <div>
            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Author</h4>
            <p class="text-base">{{ $record->author ?? 'Unknown' }}</p>
        </div>
    </div>
    
    <div>
        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Description</h4>
        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $record->description }}</p>
    </div>
    
    @if($record->screenshot)
        <div>
            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Preview</h4>
            <img src="{{ Storage::disk('public')->url($record->screenshot) }}" 
                 alt="{{ $record->name }}" 
                 class="w-full rounded-lg border border-gray-200 dark:border-gray-700">
        </div>
    @endif
    
    <div>
        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Status</h4>
        @if($record->is_active)
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                Active Theme
            </span>
        @else
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                Inactive
            </span>
        @endif
    </div>
    
    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Theme Path</h4>
        <code class="text-xs bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded">{{ $record->path }}</code>
    </div>
</div>
