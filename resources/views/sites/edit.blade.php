@extends('layouts.app')

@section('title', 'Редактор — '.$site->title)

@section('content')
@php($c = $site->content)
<div class="bar">
    <a href="{{ route('sites.index') }}">← Все сайты</a>
    <div class="actions">
        <form method="POST" action="{{ route('sites.randomize', $site) }}">@csrf
            <button class="btn" type="submit">🎲 Случайный дизайн</button></form>
        <form method="POST" action="{{ route('sites.regenerate', $site) }}">@csrf
            <button class="btn" type="submit">Сгенерировать заново</button></form>
        <form method="POST" action="{{ route('sites.publish', $site) }}">@csrf
            <button class="btn {{ $site->published ? '' : 'primary' }}" type="submit">{{ $site->published ? 'Снять с публикации' : 'Опубликовать' }}</button></form>
        <a class="btn" href="{{ route('sites.download', $site) }}">Скачать HTML</a>
        <form method="POST" action="{{ route('sites.destroy', $site) }}" onsubmit="return confirm('Удалить сайт?')">@csrf @method('DELETE')
            <button class="btn danger" type="submit">Удалить</button></form>
    </div>
</div>
@if ($site->published)
    <p class="flash">Опубликован: <a href="{{ route('sites.show', $site->slug) }}" target="_blank">{{ url('/s/'.$site->slug) }}</a></p>
@endif

<div class="grid2 editor">
    <form class="card" method="POST" action="{{ route('sites.update', $site) }}">
        @csrf @method('PUT')
        @if ($errors->any())
            <div class="err" role="alert">@foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
        @endif

        <h2>Оформление</h2>
        <label for="title">Название</label>
        <input id="title" name="title" value="{{ old('title', $site->title) }}" maxlength="80" required>
        <input type="hidden" name="city" value="{{ old('city', $site->city) }}">

        <label for="template">Шаблон</label>
        <select id="template" name="template">
            @foreach (\App\Models\Site::TEMPLATES as $k => $n)
                <option value="{{ $k }}" @selected(old('template', $site->template) === $k)>{{ $n }}</option>
            @endforeach
        </select>

        <div class="two">
            <div>
                <label>Цветовая тема</label>
                <div class="swatches" role="radiogroup" aria-label="Цветовая тема">
                    @foreach (\App\Models\Site::THEMES as $k => $n)
                        <label class="swatch" title="{{ $n }}">
                            <input type="radio" name="theme" value="{{ $k }}" @checked(old('theme', $site->theme) === $k)>
                            <span style="background:{{ ['indigo'=>'#4f46e5','emerald'=>'#047857','rose'=>'#be123c','amber'=>'#b45309','slate'=>'#334155'][$k] }}"></span>
                        </label>
                    @endforeach
                </div>
            </div>
            <div>
                <label for="font">Шрифт</label>
                <select id="font" name="font">
                    @foreach (\App\Models\Site::FONTS as $k => $n)
                        <option value="{{ $k }}" @selected(old('font', $site->font) === $k)>{{ $n }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <h2>Секции</h2>
        <p class="muted">Включайте секции и меняйте порядок номерами (1 — самая верхняя).</p>
        @foreach ($site->sections as $i => $s)
            <div class="sec">
                <label class="check"><input type="checkbox" name="enabled[{{ $s['key'] }}]" value="1" @checked($s['enabled'])> {{ $sectionNames[$s['key']] }}</label>
                <input class="num" type="number" min="1" max="99" name="order[{{ $s['key'] }}]" value="{{ $i + 1 }}" aria-label="Порядок: {{ $sectionNames[$s['key']] }}">
            </div>
        @endforeach

        <h2>Тексты</h2>
        <label for="h-title">Заголовок</label>
        <input id="h-title" name="content[hero][title]" value="{{ old('content.hero.title', $c['hero']['title']) }}">
        <label for="h-sub">Подзаголовок</label>
        <input id="h-sub" name="content[hero][subtitle]" value="{{ old('content.hero.subtitle', $c['hero']['subtitle']) }}">
        <label for="h-cta">Текст кнопки</label>
        <input id="h-cta" name="content[hero][cta]" value="{{ old('content.hero.cta', $c['hero']['cta']) }}">
        <label for="a-text">О нас</label>
        <textarea id="a-text" name="content[about][text]" rows="4">{{ old('content.about.text', $c['about']['text']) }}</textarea>

        @foreach (['features' => 'Преимущества', 'services' => 'Услуги и цены', 'testimonials' => 'Отзывы'] as $key => $label)
            <h3>{{ $label }}</h3>
            <input name="content[{{ $key }}][title]" value="{{ old("content.$key.title", $c[$key]['title']) }}" aria-label="Заголовок секции: {{ $label }}">
            @foreach ($c[$key]['items'] as $n => $item)
                <div class="item">
                    <input name="content[{{ $key }}][items][{{ $n }}][title]" value="{{ old("content.$key.items.$n.title", $item['title']) }}" aria-label="Название {{ $n + 1 }}">
                    <input name="content[{{ $key }}][items][{{ $n }}][text]" value="{{ old("content.$key.items.$n.text", $item['text']) }}" aria-label="Описание {{ $n + 1 }}">
                    @if ($key === 'services')
                        <input name="content[{{ $key }}][items][{{ $n }}][price]" value="{{ old("content.$key.items.$n.price", $item['price']) }}" aria-label="Цена {{ $n + 1 }}">
                    @endif
                </div>
            @endforeach
        @endforeach

        <h3>Контакты</h3>
        <input name="content[contacts][address]" value="{{ old('content.contacts.address', $c['contacts']['address']) }}" aria-label="Адрес">
        <input name="content[contacts][phone]" value="{{ old('content.contacts.phone', $c['contacts']['phone']) }}" aria-label="Телефон">
        <input name="content[contacts][email]" value="{{ old('content.contacts.email', $c['contacts']['email']) }}" aria-label="E-mail">

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
            <iframe title="Предпросмотр сайта" src="{{ route('sites.preview', $site) }}"></iframe>
        </div>
        <p class="muted">Предпросмотр обновляется после сохранения.</p>
    </section>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.seg button').forEach(b=>b.addEventListener('click',()=>{
  document.querySelectorAll('.seg button').forEach(x=>x.setAttribute('aria-pressed',x===b));
  document.getElementById('frame').dataset.size=b.dataset.size;}));
</script>
@endpush
