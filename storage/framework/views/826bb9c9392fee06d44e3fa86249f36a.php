<?php $__env->startSection('title', 'ИИ-конструктор сайтов'); ?>

<?php $__env->startSection('content'); ?>
<div class="grid2">
    <section class="card">
        <h1>Создать сайт</h1>
        <p class="lead">Опишите бизнес — ИИ соберёт готовую страницу, а вы доработаете её в редакторе.</p>
        <form method="POST" action="<?php echo e(route('sites.store')); ?>" novalidate>
            <?php echo csrf_field(); ?>
            <label for="title">Название</label>
            <input id="title" name="title" value="<?php echo e(old('title')); ?>" maxlength="80" placeholder="Например: Кофейня «Лист»" required>
            <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="err" role="alert"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <label for="type">Тип сайта</label>
            <select id="type" name="type" required>
                <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($key); ?>" <?php if(old('type') === $key): echo 'selected'; endif; ?>><?php echo e($name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="err" role="alert"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <label for="template">Шаблон</label>
            <select id="template" name="template">
                <option value="">Подобрать автоматически</option>
                <?php $__currentLoopData = \App\Models\Site::TEMPLATES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($k); ?>" <?php if(old('template') === $k): echo 'selected'; endif; ?>><?php echo e($n); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <p class="muted">Потом можно сменить в редакторе: там видно картинки всех шаблонов.</p>

            <label for="city">Город <span class="muted">(необязательно)</span></label>
            <input id="city" name="city" value="<?php echo e(old('city')); ?>" maxlength="60">

            <label for="prompt">Опишите сайт <span class="counter" id="count">0 / 600</span></label>
            <textarea id="prompt" name="prompt" rows="4" maxlength="600" placeholder="Небольшая кофейня в центре, свежая выпечка, работаем с 8 до 22" required><?php echo e(old('prompt')); ?></textarea>
            <?php $__errorArgs = ['prompt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="err" role="alert"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <div class="chips" aria-label="Примеры описаний">
                <button type="button" class="chip" data-type="cafe" data-title="Кофейня «Лист»" data-text="Небольшая кофейня в центре города: свежая выпечка, авторский кофе, работаем с 8 до 22.">Кофейня</button>
                <button type="button" class="chip" data-type="portfolio" data-title="Анна Смирнова" data-text="Веб-дизайнер: делаю сайты и интерфейсы для малого бизнеса, опыт шесть лет.">Портфолио</button>
                <button type="button" class="chip" data-type="courses" data-title="Школа Python" data-text="Онлайн-курсы Python для начинающих с проверкой заданий наставником.">Курсы</button>
                <button type="button" class="chip" data-type="event" data-title="Вебинар «Старт»" data-text="Однодневная конференция для начинающих разработчиков, доклады и мастер-классы.">Мероприятие</button>
            </div>

            <button class="btn primary" id="go" type="submit">Сгенерировать сайт</button>
        </form>
    </section>

    <section class="card">
        <h2>Мои сайты</h2>
        <?php $__empty_1 = true; $__currentLoopData = $sites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $site): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <article class="row">
                <div>
                    <a href="<?php echo e(route('sites.edit', $site)); ?>"><strong><?php echo e($site->title); ?></strong></a>
                    <div class="muted"><?php echo e($types[$site->type] ?? $site->type); ?> ·
                        <span class="badge <?php echo e($site->published ? 'on' : ''); ?>"><?php echo e($site->published ? 'опубликован' : 'черновик'); ?></span></div>
                </div>
                <a class="btn" href="<?php echo e(route('sites.edit', $site)); ?>">Открыть</a>
            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="empty">Пока нет сайтов.<br>Заполните форму или выберите пример.</div>
        <?php endif; ?>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
const f=document.querySelector('form'),t=document.getElementById('prompt'),c=document.getElementById('count');
const upd=()=>c.textContent=t.value.length+' / 600';t.addEventListener('input',upd);upd();
document.querySelectorAll('.chip').forEach(b=>b.addEventListener('click',()=>{
  f.title.value=b.dataset.title;f.type.value=b.dataset.type;t.value=b.dataset.text;upd();t.focus();}));
f.addEventListener('submit',()=>{const g=document.getElementById('go');g.disabled=true;g.textContent='Генерируем…';});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/user/work/ai_constructor_websites/resources/views/sites/index.blade.php ENDPATH**/ ?>