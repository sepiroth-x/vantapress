<div
    <?php echo e($attributes
            ->merge([
                'id' => $getId(),
            ], escape: false)
            ->merge($getExtraAttributes(), escape: false)); ?>

>
    <?php echo e($getChildComponentContainer()); ?>

</div>
<?php /**PATH C:\Users\sepirothx\Documents\3. Laravel Development\vantapress\vendor\filament\forms\resources\views/components/group.blade.php ENDPATH**/ ?>