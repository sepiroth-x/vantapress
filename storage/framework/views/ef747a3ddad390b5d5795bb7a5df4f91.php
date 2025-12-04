
<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <title><?php echo $__env->yieldContent('title', config('app.name', 'VantaPress')); ?> - The Villain Arise</title>
    
    
    <?php echo $__env->yieldContent('meta'); ?>
    
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        villain: {
                            50: '#fef2f2',
                            100: '#fee2e2',
                            200: '#fecaca',
                            300: '#fca5a5',
                            400: '#f87171',
                            500: '#ef4444',
                            600: '#dc2626',
                            700: '#b91c1c',
                            800: '#991b1b',
                            900: '#7f1d1d',
                        }
                    }
                }
            }
        }
    </script>
    
    
    <link rel="stylesheet" href="<?php echo e(asset('themes/TheVillainArise/assets/css/theme.css')); ?>">
    
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">
    
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="bg-gray-900 text-gray-100 font-mono">
    
    
    <?php echo $__env->make('theme.partials::header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(function_exists('vp_get_widget_area')): ?>
        <?php echo vp_get_widget_area('header'); ?>

    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    
    
    <main class="min-h-screen">
        <?php echo $__env->yieldContent('content'); ?>
    </main>
    
    
    <?php echo $__env->make('theme.partials::footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(function_exists('vp_get_widget_area')): ?>
        <?php echo vp_get_widget_area('footer'); ?>

    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    
    
    <script src="<?php echo e(asset('themes/TheVillainArise/assets/js/theme.js')); ?>"></script>
    
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\sepirothx\Documents\3. Laravel Development\vantapress\themes/TheVillainArise/layouts/main.blade.php ENDPATH**/ ?>