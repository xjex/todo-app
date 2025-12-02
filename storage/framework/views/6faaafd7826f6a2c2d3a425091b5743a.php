<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo e(config('app.name', 'Todo App')); ?></title>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/js/app.js']); ?>
    </head>
    <body>
        <div id="app"></div>
    </body>
</html>

<?php /**PATH C:\Users\druxb\Desktop\exam\todo-2025\resources\views/app.blade.php ENDPATH**/ ?>