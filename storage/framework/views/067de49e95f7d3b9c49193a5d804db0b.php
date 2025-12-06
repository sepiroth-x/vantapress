<div class="space-y-4">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Version</h4>
            <p class="text-base"><?php echo e($record->version); ?></p>
        </div>
        
        <div>
            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Author</h4>
            <p class="text-base"><?php echo e($record->author ?? 'Unknown'); ?></p>
        </div>
    </div>
    
    <div>
        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Description</h4>
        <p class="text-sm text-gray-700 dark:text-gray-300"><?php echo e($record->description); ?></p>
    </div>
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($record->screenshot): ?>
        <div>
            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Preview</h4>
            <img src="<?php echo e(Storage::disk('public')->url($record->screenshot)); ?>" 
                 alt="<?php echo e($record->name); ?>" 
                 class="w-full rounded-lg border border-gray-200 dark:border-gray-700">
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    
    <div>
        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Status</h4>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($record->is_active): ?>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                Active Theme
            </span>
        <?php else: ?>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                Inactive
            </span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    
    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Theme Path</h4>
        <code class="text-xs bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded"><?php echo e($record->path); ?></code>
    </div>
</div>
<?php /**PATH C:\Users\sepirothx\Documents\3. Laravel Development\vantapress\resources\views/filament/resources/theme-resource/view-theme.blade.php ENDPATH**/ ?>