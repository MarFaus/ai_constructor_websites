<!doctype html>
<html lang="ru">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo e($site->title); ?></title>
<?php
    $themes = [
        'indigo' => ['#4f46e5', '#eef2ff'],
        'emerald' => ['#047857', '#ecfdf5'],
        'rose' => ['#be123c', '#fff1f2'],
        'amber' => ['#b45309', '#fffbeb'],
        'slate' => ['#334155', '#f1f5f9'],
    ];
    $fonts = [
        'sans' => 'system-ui, -apple-system, "Segoe UI", Roboto, sans-serif',
        'serif' => 'Georgia, "Times New Roman", serif',
        'mono' => 'ui-monospace, Consolas, monospace',
    ];
    [$accent, $soft] = $themes[$site->theme] ?? $themes['indigo'];
    $c = $site->content;
?>
<style>
:root{--accent:<?php echo e($accent); ?>;--soft:<?php echo e($soft); ?>;--ink:#1f2937;--muted:#6b7280}
*{box-sizing:border-box}
html{scroll-behavior:smooth}
body{margin:0;font-family:<?php echo $fonts[$site->font] ?? $fonts['sans']; ?>;color:var(--ink);line-height:1.65}
.in{max-width:1040px;margin:0 auto;padding:0 24px}
nav{position:sticky;top:0;background:rgba(255,255,255,.92);backdrop-filter:blur(8px);border-bottom:1px solid #e5e7eb;z-index:2}
nav .in{display:flex;justify-content:space-between;align-items:center;height:60px}
nav b{color:var(--accent);font-size:1.1rem}
nav a{color:var(--ink);text-decoration:none;font-weight:600;padding:8px 16px;border:2px solid var(--accent);border-radius:999px}
section{padding:80px 0}
section:nth-of-type(even){background:var(--soft)}
h1{font-size:clamp(2rem,6vw,3.4rem);line-height:1.1;margin:0 0 16px;letter-spacing:-.02em}
h2{font-size:clamp(1.5rem,4vw,2rem);margin:0 0 32px;letter-spacing:-.01em}
h2::after{content:"";display:block;width:48px;height:4px;border-radius:2px;background:var(--accent);margin-top:12px}
.hero{background:linear-gradient(135deg,var(--accent),color-mix(in srgb,var(--accent) 55%,#000));color:#fff;text-align:center;padding:120px 0}
.hero p{font-size:1.25rem;opacity:.92;margin:0 auto 40px;max-width:640px}
.btn{display:inline-block;background:#fff;color:var(--accent);padding:14px 32px;border-radius:999px;text-decoration:none;font-weight:700;box-shadow:0 6px 20px rgba(0,0,0,.2);transition:transform .15s}
.btn:hover{transform:translateY(-2px)}
.cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px}
.card{background:#fff;border:1px solid #e5e7eb;border-radius:16px;padding:24px;box-shadow:0 2px 12px rgba(0,0,0,.04);transition:transform .15s,box-shadow .15s}
.card:hover{transform:translateY(-3px);box-shadow:0 10px 28px rgba(0,0,0,.09)}
.card h3{margin:0 0 8px;color:var(--accent)}
.card p{margin:0}
.price{font-weight:800;font-size:1.15rem;margin-top:16px}
.quote{font-style:italic;margin-bottom:12px!important}
footer{background:#111827;color:#9ca3af;text-align:center;padding:32px}

.hero-art{display:none}
/* ---- Шаблон: две колонки ---- */
.t-split .hero{text-align:left;padding:96px 0}
.t-split .hero .in{display:grid;grid-template-columns:1.2fr 1fr;gap:48px;align-items:center}
.t-split .hero p{margin-left:0}
.t-split .hero-art{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.t-split .hero-art span{display:block;aspect-ratio:1;border-radius:24px;background:rgba(255,255,255,.18);border:1px solid rgba(255,255,255,.35)}
.t-split .hero-art span:nth-child(1){border-radius:50%}
.t-split .hero-art span:nth-child(2){transform:translateY(24px)}
.t-split .hero-art span:nth-child(3){grid-column:span 2;aspect-ratio:3/1}
@media(max-width:700px){.t-split .hero .in{grid-template-columns:1fr}.t-split .hero-art{display:none}}
/* ---- Шаблон: минимализм ---- */
.t-minimal nav{border:0;background:#fff}
.t-minimal .hero{background:#fff;color:var(--ink);text-align:left;padding:104px 0 72px}
.t-minimal .hero .in{max-width:760px}
.t-minimal .hero h1{font-size:clamp(2.4rem,7vw,4.2rem)}
.t-minimal .hero p{margin-left:0;color:var(--muted)}
.t-minimal .btn{background:var(--accent);color:#fff;box-shadow:none;border-radius:6px}
.t-minimal section:nth-of-type(even){background:#fff}
.t-minimal .card{border:0;border-top:3px solid var(--accent);border-radius:0;box-shadow:none;padding:20px 0 0;background:transparent}
.t-minimal h2::after{display:none}
.t-minimal nav a{border-radius:6px}
/* ---- Шаблон: тёмный ---- */
.t-bold{background:#0b0f19;color:#e5e7eb}
.t-bold nav{background:rgba(11,15,25,.9);border-color:#1f2937}
.t-bold nav a{color:#fff}
.t-bold .hero{background:radial-gradient(circle at 30% 20%,color-mix(in srgb,var(--accent) 70%,#fff),transparent 60%),#0b0f19}
.t-bold section:nth-of-type(even){background:#111827}
.t-bold .card{background:#161d2e;border-color:#1f2937;box-shadow:none}
.t-bold .card h3{color:color-mix(in srgb,var(--accent) 45%,#fff)}
.t-bold .btn{background:var(--accent);color:#fff}
.t-bold .price{color:#fff}
.t-bold h2,.t-bold h1{color:#fff}
/* ---- Шаблон: карточки ---- */
.t-cards{background:var(--soft)}
.t-cards nav{margin:16px auto;max-width:1040px;border:1px solid #e5e7eb;border-radius:999px}
.t-cards .hero{margin:0 16px;border-radius:32px;padding:88px 0}
.t-cards section,.t-cards section:nth-of-type(even){background:transparent;padding:48px 0}
.t-cards section>.in{background:#fff;border-radius:28px;padding:40px;box-shadow:0 4px 24px rgba(0,0,0,.05)}
.t-cards .card{background:var(--soft);border:0;box-shadow:none}
.t-cards footer{background:transparent;color:var(--muted)}
@media(max-width:600px){.t-cards section>.in{padding:24px}}
@media(max-width:600px){section{padding:56px 0}.hero{padding:80px 0}}
@media(prefers-reduced-motion:reduce){*{transition:none!important}}
</style>
</head>
<body class="t-<?php echo e($site->template ?? 'classic'); ?>">
<nav><div class="in"><b><?php echo e($site->title); ?></b><a href="#contacts"><?php echo e($c['hero']['cta']); ?></a></div></nav>
<?php $__currentLoopData = $site->enabledSections(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php switch($s['key']):
        case ('hero'): ?>
            <div class="hero"><div class="in">
                <div class="hero-text">
                <h1><?php echo e($c['hero']['title']); ?></h1>
                <p><?php echo e($c['hero']['subtitle']); ?></p>
                <a class="btn" href="#contacts"><?php echo e($c['hero']['cta']); ?></a>
                </div>
                <div class="hero-art" aria-hidden="true"><span></span><span></span><span></span></div>
            </div></div>
            <?php break; ?>
        <?php case ('about'): ?>
            <section><div class="in"><h2><?php echo e($c['about']['title']); ?></h2><p><?php echo e($c['about']['text']); ?></p></div></section>
            <?php break; ?>
        <?php case ('features'): ?>
            <section><div class="in"><h2><?php echo e($c['features']['title']); ?></h2><div class="cards">
                <?php $__currentLoopData = $c['features']['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div class="card"><h3><?php echo e($i['title']); ?></h3><p><?php echo e($i['text']); ?></p></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div></div></section>
            <?php break; ?>
        <?php case ('services'): ?>
            <section><div class="in"><h2><?php echo e($c['services']['title']); ?></h2><div class="cards">
                <?php $__currentLoopData = $c['services']['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div class="card"><h3><?php echo e($i['title']); ?></h3><p><?php echo e($i['text']); ?></p><div class="price"><?php echo e($i['price'] ?? ''); ?></div></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div></div></section>
            <?php break; ?>
        <?php case ('testimonials'): ?>
            <section><div class="in"><h2><?php echo e($c['testimonials']['title']); ?></h2><div class="cards">
                <?php $__currentLoopData = $c['testimonials']['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div class="card"><p class="quote">«<?php echo e($i['text']); ?>»</p><strong><?php echo e($i['title']); ?></strong></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div></div></section>
            <?php break; ?>
        <?php case ('contacts'): ?>
            <section id="contacts"><div class="in"><h2><?php echo e($c['contacts']['title']); ?></h2>
                <p><?php echo e($c['contacts']['address']); ?><br><?php echo e($c['contacts']['phone']); ?><br><?php echo e($c['contacts']['email']); ?></p>
            </div></section>
            <?php break; ?>
    <?php endswitch; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<footer>© <?php echo e(date('Y')); ?> <?php echo e($site->title); ?></footer>
</body>
</html>
<?php /**PATH /home/user/work/ai_constructor_websites/resources/views/site.blade.php ENDPATH**/ ?>