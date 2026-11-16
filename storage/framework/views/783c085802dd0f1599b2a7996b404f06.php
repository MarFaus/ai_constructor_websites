<?php $__env->startSection('title', 'Редактор — '.$site->title); ?>

<?php $__env->startSection('content'); ?>
<?php ($c = $site->content); ?>
<div class="bar">
    <a href="<?php echo e(route('sites.index')); ?>">← Все сайты</a>
    <div class="actions">
        <form method="POST" action="<?php echo e(route('sites.randomize', $site)); ?>"><?php echo csrf_field(); ?>
            <button class="btn" type="submit">🎲 Случайный дизайн</button></form>
        <form method="POST" action="<?php echo e(route('sites.regenerate', $site)); ?>"><?php echo csrf_field(); ?>
            <button class="btn" type="submit">Сгенерировать заново</button></form>
        <form method="POST" action="<?php echo e(route('sites.publish', $site)); ?>"><?php echo csrf_field(); ?>
            <button class="btn <?php echo e($site->published ? '' : 'primary'); ?>" type="submit"><?php echo e($site->published ? 'Снять с публикации' : 'Опубликовать'); ?></button></form>
        <a class="btn" href="<?php echo e(route('sites.download', $site)); ?>">Скачать HTML</a>
        <form method="POST" action="<?php echo e(route('sites.destroy', $site)); ?>" onsubmit="return confirm('Удалить сайт?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
            <button class="btn danger" type="submit">Удалить</button></form>
    </div>
</div>
<?php if($site->published): ?>
    <p class="flash">Опубликован: <a href="<?php echo e(route('sites.show', $site->slug)); ?>" target="_blank"><?php echo e(url('/s/'.$site->slug)); ?></a></p>
<?php endif; ?>

<div class="grid2 editor">
    <form class="card" method="POST" action="<?php echo e(route('sites.update', $site)); ?>">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
        <?php if($errors->any()): ?>
            <div class="err" role="alert"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div><?php echo e($e); ?></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div>
        <?php endif; ?>

        <h2>Оформление</h2>
        <label for="title">Название</label>
        <input id="title" name="title" value="<?php echo e(old('title', $site->title)); ?>" maxlength="80" required>
        <input type="hidden" name="city" value="<?php echo e(old('city', $site->city)); ?>">

        <fieldset class="tpls">
            <legend>Шаблон сайта</legend>
            <?php $__currentLoopData = \App\Models\Site::TEMPLATES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <label class="tpl">
                    <input type="radio" name="template" value="<?php echo e($k); ?>" <?php if(old('template', $site->template) === $k): echo 'checked'; endif; ?>>
                    <img src="<?php echo e(asset('img/tpl-'.$k.'.jpg')); ?>" alt="" loading="lazy">
                    <span><?php echo e($n); ?></span>
                </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </fieldset>
        <p class="muted">Выберите шаблон, тему или шрифт — предпросмотр справа обновится сразу. Чтобы оставить, нажмите «Сохранить изменения».</p>

        <div class="two">
            <div>
                <label>Цветовая тема</label>
                <div class="swatches" role="radiogroup" aria-label="Цветовая тема">
                    <?php $__currentLoopData = \App\Models\Site::THEMES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="swatch" title="<?php echo e($n); ?>">
                            <input type="radio" name="theme" value="<?php echo e($k); ?>" <?php if(old('theme', $site->theme) === $k): echo 'checked'; endif; ?>>
                            <span style="background:<?php echo e(['indigo'=>'#4f46e5','emerald'=>'#047857','rose'=>'#be123c','amber'=>'#b45309','slate'=>'#334155'][$k]); ?>"></span>
                        </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <div>
                <label for="font">Шрифт</label>
                <select id="font" name="font">
                    <?php $__currentLoopData = \App\Models\Site::FONTS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($k); ?>" <?php if(old('font', $site->font) === $k): echo 'selected'; endif; ?>><?php echo e($n); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <h2>Секции</h2>
        <p class="muted">Включайте секции и меняйте порядок номерами (1 — самая верхняя).</p>
        <?php $__currentLoopData = $site->sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="sec">
                <label class="check"><input type="checkbox" name="enabled[<?php echo e($s['key']); ?>]" value="1" <?php if($s['enabled']): echo 'checked'; endif; ?>> <?php echo e($sectionNames[$s['key']]); ?></label>
                <input class="num" type="number" min="1" max="99" name="order[<?php echo e($s['key']); ?>]" value="<?php echo e($i + 1); ?>" aria-label="Порядок: <?php echo e($sectionNames[$s['key']]); ?>">
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <h2>Тексты</h2>
        <label for="h-title">Заголовок</label>
        <input id="h-title" name="content[hero][title]" value="<?php echo e(old('content.hero.title', $c['hero']['title'])); ?>">
        <label for="h-sub">Подзаголовок</label>
        <input id="h-sub" name="content[hero][subtitle]" value="<?php echo e(old('content.hero.subtitle', $c['hero']['subtitle'])); ?>">
        <label for="h-cta">Текст кнопки</label>
        <input id="h-cta" name="content[hero][cta]" value="<?php echo e(old('content.hero.cta', $c['hero']['cta'])); ?>">
        <label for="a-text">О нас</label>
        <textarea id="a-text" name="content[about][text]" rows="4"><?php echo e(old('content.about.text', $c['about']['text'])); ?></textarea>

        <?php $__currentLoopData = ['features' => 'Преимущества', 'services' => 'Услуги и цены', 'testimonials' => 'Отзывы']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <h3><?php echo e($label); ?></h3>
            <input name="content[<?php echo e($key); ?>][title]" value="<?php echo e(old("content.$key.title", $c[$key]['title'])); ?>" aria-label="Заголовок секции: <?php echo e($label); ?>">
            <?php $__currentLoopData = $c[$key]['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="item">
                    <input name="content[<?php echo e($key); ?>][items][<?php echo e($n); ?>][title]" value="<?php echo e(old("content.$key.items.$n.title", $item['title'])); ?>" aria-label="Название <?php echo e($n + 1); ?>">
                    <input name="content[<?php echo e($key); ?>][items][<?php echo e($n); ?>][text]" value="<?php echo e(old("content.$key.items.$n.text", $item['text'])); ?>" aria-label="Описание <?php echo e($n + 1); ?>">
                    <?php if($key === 'services'): ?>
                        <input name="content[<?php echo e($key); ?>][items][<?php echo e($n); ?>][price]" value="<?php echo e(old("content.$key.items.$n.price", $item['price'])); ?>" aria-label="Цена <?php echo e($n + 1); ?>">
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <h3>Контакты</h3>
        <input name="content[contacts][address]" value="<?php echo e(old('content.contacts.address', $c['contacts']['address'])); ?>" aria-label="Адрес">
        <input name="content[contacts][phone]" value="<?php echo e(old('content.contacts.phone', $c['contacts']['phone'])); ?>" aria-label="Телефон">
        <input name="content[contacts][email]" value="<?php echo e(old('content.contacts.email', $c['contacts']['email'])); ?>" aria-label="E-mail">

        <div class="savebar"><button class="btn primary" type="submit">Сохранить изменения</button></div>
    </form>

    <section class="card preview">
        <div class="ph">
            <h2>Предпросмотр</h2>
            <div class="seg" role="group" aria-label="Размер экрана">
                <button type="button" data-size="desktop" aria-pressed="true">Компьютер</button>
                <button type="button" data-size="tablet" aria-pressed="false">Планшет</button>
                <button type="button" data-size="mobile" aria-pressed="false">Телефон</button>
            </div>
        </div>
        <div class="frame" id="frame" data-size="desktop">
            <iframe title="Предпросмотр сайта" src="<?php echo e(route('sites.preview', $site)); ?>"></iframe>
        </div>
        <p class="muted" id="unsaved" hidden>Это несохранённый вариант. Нажмите «Сохранить изменения» слева.</p>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.querySelectorAll('.seg button').forEach(b=>b.addEventListener('click',()=>{
  document.querySelectorAll('.seg button').forEach(x=>x.setAttribute('aria-pressed',x===b));
  document.getElementById('frame').dataset.size=b.dataset.size;}));
const form=document.querySelector('form.card');
const ifr=document.querySelector('.frame iframe'),base=ifr.src.split('?')[0],note=document.getElementById('unsaved');
const pick=n=>(form.querySelector('[name='+n+']:checked')||form.querySelector('[name='+n+']')).value;
const refresh=()=>{ifr.src=base+'?template='+pick('template')+'&theme='+pick('theme')+'&font='+pick('font');note.hidden=false;};
['template','theme','font'].forEach(n=>form.querySelectorAll('[name='+n+']').forEach(e=>e.addEventListener('change',refresh)));
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/user/work/ai_constructor_websites/resources/views/sites/edit.blade.php ENDPATH**/ ?>