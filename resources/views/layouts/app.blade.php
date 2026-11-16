<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'ИИ-конструктор сайтов')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<header class="top">
    <a class="brand" href="{{ route('sites.index') }}">Конструктор<span>.ИИ</span></a>
    <span class="muted">Опишите сайт — получите готовую страницу</span>
</header>
<main class="wrap">
    @if (session('status'))
        <div class="flash" role="status">{{ session('status') }}</div>
    @endif
    @yield('content')
</main>
@stack('scripts')
</body>
</html>
