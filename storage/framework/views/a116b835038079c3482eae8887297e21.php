<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $__env->yieldContent('title', 'ИИ-конструктор сайтов'); ?></title>
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
</head>
<body>
<header class="top">
    <a class="brand" href="<?php echo e(route('sites.index')); ?>">Конструктор<span>.ИИ</span></a>
    <span class="muted">Опишите сайт — получите готовую страницу</span>
</header>
<main class="wrap">
    <?php if(session('status')): ?>
        <div class="flash" role="status"><?php echo e(session('status')); ?></div>
    <?php endif; ?>
    <?php echo $__env->yieldContent('content'); ?>
</main>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /home/user/work/ai_constructor_websites/resources/views/layouts/app.blade.php ENDPATH**/ ?>