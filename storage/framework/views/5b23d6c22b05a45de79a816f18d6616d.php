<?php if (isset($component)) { $__componentOriginald489e48d6214ecaf87e4b6a8ce684ad1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald489e48d6214ecaf87e4b6a8ce684ad1 = $attributes; } ?>
<?php $component = Filament\View\LegacyComponents\Widget::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::widget'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Filament\View\LegacyComponents\Widget::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'fi-wi-attribution']); ?>
    <?php if (isset($component)) { $__componentOriginal9b945b32438afb742355861768089b04 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9b945b32438afb742355861768089b04 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.card','data' => ['class' => 'w-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-full']); ?>
        <div class="p-6">
            <!-- Profile and Info Section -->
            <div class="flex items-start space-x-4 mb-6">
                <div class="flex-shrink-0">
                    <!-- Shield icon representing Sepiroth X Villainous -->
                    <svg class="w-24 h-24 text-crimson-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5zm0 18c-3.31-.98-6-4.65-6-9V8.3l6-3.11v14.82z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                        VantaPress CMS - Open Source
                    </h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Created with passion by <strong class="text-crimson-500">Sepiroth X Villainous</strong> a.k.a <strong class="text-crimson-500">Xenroth Vantablack</strong> (Richard Cebel Cupal, LPT)
                    </p>
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-500">
                        A powerful modular CMS built with Laravel & Filament. All themes and modules are open source and free to use.
                    </p>
                </div>
            </div>
            
            <!-- Social Links Section -->
            <div class="flex flex-wrap gap-2 mb-4">
                <a href="https://github.com/sepiroth-x" target="_blank" 
                   class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.477 2 2 6.477 2 12c0 4.42 2.865 8.17 6.839 9.49.5.092.682-.217.682-.482 0-.237-.008-.866-.013-1.7-2.782.603-3.369-1.34-3.369-1.34-.454-1.156-1.11-1.463-1.11-1.463-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.578 9.578 0 0112 6.836c.85.004 1.705.114 2.504.336 1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.203 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.743 0 .267.18.578.688.48C19.138 20.167 22 16.418 22 12c0-5.523-4.477-10-10-10z"/>
                    </svg>
                    GitHub
                </a>
                <a href="https://www.facebook.com/sepirothx/" target="_blank" 
                   class="inline-flex items-center justify-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg transition">
                    Facebook
                </a>
                <a href="https://x.com/sepirothx000" target="_blank" 
                   class="inline-flex items-center justify-center px-3 py-2 bg-black hover:bg-gray-900 text-white text-xs font-medium rounded-lg transition">
                    Twitter
                </a>
                <a href="mailto:chardy.tsadiq02@gmail.com" 
                   class="inline-flex items-center justify-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-lg transition">
                    Email
                </a>
            </div>
        </div>
        
        <!-- Footer Contact Info -->
        <div class="px-6 pb-6 pt-4 border-t border-gray-200 dark:border-gray-700">
            <p class="text-xs text-gray-500 dark:text-gray-500 text-center">
                📱 Contact: +63 915 0388 448 | 
                💻 GitHub: <a href="https://github.com/sepiroth-x" target="_blank" class="text-crimson-500 hover:underline">sepiroth-x</a> | 
                📧 Email: chardy.tsadiq02@gmail.com
            </p>
        </div>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9b945b32438afb742355861768089b04)): ?>
<?php $attributes = $__attributesOriginal9b945b32438afb742355861768089b04; ?>
<?php unset($__attributesOriginal9b945b32438afb742355861768089b04); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9b945b32438afb742355861768089b04)): ?>
<?php $component = $__componentOriginal9b945b32438afb742355861768089b04; ?>
<?php unset($__componentOriginal9b945b32438afb742355861768089b04); ?>
<?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald489e48d6214ecaf87e4b6a8ce684ad1)): ?>
<?php $attributes = $__attributesOriginald489e48d6214ecaf87e4b6a8ce684ad1; ?>
<?php unset($__attributesOriginald489e48d6214ecaf87e4b6a8ce684ad1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald489e48d6214ecaf87e4b6a8ce684ad1)): ?>
<?php $component = $__componentOriginald489e48d6214ecaf87e4b6a8ce684ad1; ?>
<?php unset($__componentOriginald489e48d6214ecaf87e4b6a8ce684ad1); ?>
<?php endif; ?>

<style>
    .text-crimson-500 {
        color: #D40026;
    }
    .ring-crimson-500 {
        --tw-ring-color: #D40026;
    }
    .dark .fi-wi-attribution {
        background: transparent;
    }
</style>
<?php /**PATH C:\Users\sepirothx\Documents\3. Laravel Development\vantapress\resources\views/filament/widgets/attribution-widget.blade.php ENDPATH**/ ?>