@extends('layouts.app')

@section('title', 'ИИ-конструктор сайтов')

@section('content')
<div class="grid2">
    <section class="card">
        <h1>Создать сайт</h1>
        <p class="lead">Опишите бизнес — ИИ соберёт готовую страницу, а вы доработаете её в редакторе.</p>
        <form method="POST" action="{{ route('sites.store') }}" novalidate>
            @csrf
            <label for="title">Название</label>
            <input id="title" name="title" value="{{ old('title') }}" maxlength="80" placeholder="Например: Кофейня «Лист»" required>
            @error('title')<p class="err" role="alert">{{ $message }}</p>@enderror

            <label for="type">Тип сайта</label>
            <select id="type" name="type" required>
                @foreach ($types as $key => $name)
                    <option value="{{ $key }}" @selected(old('type') === $key)>{{ $name }}</option>
                @endforeach
            </select>
            @error('type')<p class="err" role="alert">{{ $message }}</p>@enderror

            <label for="template">Шаблон</label>
            <select id="template" name="template">
                <option value="">Подобрать автоматически</option>
                @foreach (\App\Models\Site::TEMPLATES as $k => $n)
                    <option value="{{ $k }}" @selected(old('template') === $k)>{{ $n }}</option>
                @endforeach
            </select>
            <p class="muted">Потом можно сменить в редакторе: там видно картинки всех шаблонов.</p>

            <label for="city">Город <span class="muted">(необязательно)</span></label>
            <input id="city" name="city" value="{{ old('city') }}" maxlength="60">

            <label for="prompt">Опишите сайт <span class="counter" id="count">0 / 600</span></label>
            <textarea id="prompt" name="prompt" rows="4" maxlength="600" placeholder="Небольшая кофейня в центре, свежая выпечка, работаем с 8 до 22" required>{{ old('prompt') }}</textarea>
            @error('prompt')<p class="err" role="alert">{{ $message }}</p>@enderror
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
        @forelse ($sites as $site)
            <article class="row">
                <div>
                    <a href="{{ route('sites.edit', $site) }}"><strong>{{ $site->title }}</strong></a>
                    <div class="muted">{{ $types[$site->type] ?? $site->type }} ·
                        <span class="badge {{ $site->published ? 'on' : '' }}">{{ $site->published ? 'опубликован' : 'черновик' }}</span></div>
                </div>
                <a class="btn" href="{{ route('sites.edit', $site) }}">Открыть</a>
            </article>
        @empty
            <div class="empty">Пока нет сайтов.<br>Заполните форму или выберите пример.</div>
        @endforelse
    </section>
</div>
@endsection

@push('scripts')
<script>
const f=document.querySelector('form'),t=document.getElementById('prompt'),c=document.getElementById('count');
const upd=()=>c.textContent=t.value.length+' / 600';t.addEventListener('input',upd);upd();
document.querySelectorAll('.chip').forEach(b=>b.addEventListener('click',()=>{
  f.title.value=b.dataset.title;f.type.value=b.dataset.type;t.value=b.dataset.text;upd();t.focus();}));
f.addEventListener('submit',()=>{const g=document.getElementById('go');g.disabled=true;g.textContent='Генерируем…';});
</script>
@endpush
