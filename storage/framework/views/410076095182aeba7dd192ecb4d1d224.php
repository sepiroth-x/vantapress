
<header class="villain-header bg-gray-950 border-b border-villain-600/30 sticky top-0 z-50 backdrop-blur-sm bg-gray-950/90">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-20">
            
            
            <div class="flex items-center">
                <a href="<?php echo e(url('/')); ?>" class="flex items-center space-x-3 group">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(function_exists('vp_get_theme_setting')): ?>
                        <?php
                            $logo = vp_get_theme_setting('logo');
                        ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($logo): ?>
                            <img src="<?php echo e(asset($logo)); ?>" alt="<?php echo e(config('app.name')); ?>" class="h-10 w-auto">
                        <?php else: ?>
                            <div class="text-2xl font-black font-orbitron">
                                <span class="text-villain-500 group-hover:text-villain-400 transition">VILLAIN</span>
                                <span class="text-gray-100">PRESS</span>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php else: ?>
                        <div class="text-2xl font-black font-orbitron">
                            <span class="text-villain-500 group-hover:text-villain-400 transition">VILLAIN</span>
                            <span class="text-gray-100">PRESS</span>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </a>
            </div>
            
            
            <nav class="hidden md:flex items-center space-x-8">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(function_exists('vp_get_menu')): ?>
                    <?php
                        $primaryMenu = vp_get_menu('primary');
                    ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($primaryMenu && count($primaryMenu) > 0): ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $primaryMenu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e($item['url']); ?>" 
                               class="text-gray-300 hover:text-villain-500 transition font-medium uppercase text-sm tracking-wider
                                      <?php echo e(request()->is(trim($item['url'], '/')) ? 'text-villain-500' : ''); ?>">
                                <?php echo e($item['title']); ?>

                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php else: ?>
                        
                        <a href="<?php echo e(url('/')); ?>" class="text-gray-300 hover:text-villain-500 transition font-medium uppercase text-sm tracking-wider">Home</a>
                        <a href="<?php echo e(url('/pages')); ?>" class="text-gray-300 hover:text-villain-500 transition font-medium uppercase text-sm tracking-wider">Pages</a>
                        <a href="<?php echo e(url('/about')); ?>" class="text-gray-300 hover:text-villain-500 transition font-medium uppercase text-sm tracking-wider">About</a>
                        <a href="<?php echo e(url('/contact')); ?>" class="text-gray-300 hover:text-villain-500 transition font-medium uppercase text-sm tracking-wider">Contact</a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php else: ?>
                    
                    <a href="<?php echo e(url('/')); ?>" class="text-gray-300 hover:text-villain-500 transition font-medium uppercase text-sm tracking-wider">Home</a>
                    <a href="<?php echo e(url('/pages')); ?>" class="text-gray-300 hover:text-villain-500 transition font-medium uppercase text-sm tracking-wider">Pages</a>
                    <a href="<?php echo e(url('/about')); ?>" class="text-gray-300 hover:text-villain-500 transition font-medium uppercase text-sm tracking-wider">About</a>
                    <a href="<?php echo e(url('/contact')); ?>" class="text-gray-300 hover:text-villain-500 transition font-medium uppercase text-sm tracking-wider">Contact</a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </nav>
            
            
            <div class="flex items-center space-x-4">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(url('/admin')); ?>" 
                       class="bg-villain-600 hover:bg-villain-700 text-white px-4 py-2 rounded font-medium text-sm transition">
                        Admin Panel
                    </a>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(function_exists('vp_get_current_user_profile')): ?>
                        <a href="<?php echo e(url('/profile')); ?>" 
                           class="text-gray-300 hover:text-villain-500 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" 
                       class="text-gray-300 hover:text-villain-500 transition font-medium text-sm">
                        Login
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                
                
                <button id="mobile-menu-toggle" class="md:hidden text-gray-300 hover:text-villain-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        
        <div id="mobile-menu" class="hidden md:hidden pb-4 border-t border-villain-600/30 mt-2 pt-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(function_exists('vp_get_menu')): ?>
                <?php
                    $primaryMenu = vp_get_menu('primary');
                ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($primaryMenu && count($primaryMenu) > 0): ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $primaryMenu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e($item['url']); ?>" 
                           class="block py-2 text-gray-300 hover:text-villain-500 transition font-medium uppercase text-sm tracking-wider">
                            <?php echo e($item['title']); ?>

                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php else: ?>
                    <a href="<?php echo e(url('/')); ?>" class="block py-2 text-gray-300 hover:text-villain-500 transition">Home</a>
                    <a href="<?php echo e(url('/pages')); ?>" class="block py-2 text-gray-300 hover:text-villain-500 transition">Pages</a>
                    <a href="<?php echo e(url('/about')); ?>" class="block py-2 text-gray-300 hover:text-villain-500 transition">About</a>
                    <a href="<?php echo e(url('/contact')); ?>" class="block py-2 text-gray-300 hover:text-villain-500 transition">Contact</a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php else: ?>
                <a href="<?php echo e(url('/')); ?>" class="block py-2 text-gray-300 hover:text-villain-500 transition">Home</a>
                <a href="<?php echo e(url('/pages')); ?>" class="block py-2 text-gray-300 hover:text-villain-500 transition">Pages</a>
                <a href="<?php echo e(url('/about')); ?>" class="block py-2 text-gray-300 hover:text-villain-500 transition">About</a>
                <a href="<?php echo e(url('/contact')); ?>" class="block py-2 text-gray-300 hover:text-villain-500 transition">Contact</a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</header>
<?php /**PATH C:\Users\sepirothx\Documents\3. Laravel Development\vantapress\themes/TheVillainArise/partials/header.blade.php ENDPATH**/ ?>