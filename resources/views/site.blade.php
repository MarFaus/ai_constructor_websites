<!doctype html>
<html lang="ru">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $site->title }}</title>
@php
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
@endphp
<style>
:root{--accent:{{ $accent }};--soft:{{ $soft }};--ink:#1f2937;--muted:#6b7280}
*{box-sizing:border-box}
html{scroll-behavior:smooth}
body{margin:0;font-family:{!! $fonts[$site->font] ?? $fonts['sans'] !!};color:var(--ink);line-height:1.65}
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
@media(max-width:600px){section{padding:56px 0}.hero{padding:80px 0}}
@media(prefers-reduced-motion:reduce){*{transition:none!important}}
</style>
</head>
<body>
<nav><div class="in"><b>{{ $site->title }}</b><a href="#contacts">{{ $c['hero']['cta'] }}</a></div></nav>
@foreach ($site->enabledSections() as $s)
    @switch($s['key'])
        @case('hero')
            <div class="hero"><div class="in">
                <h1>{{ $c['hero']['title'] }}</h1>
                <p>{{ $c['hero']['subtitle'] }}</p>
                <a class="btn" href="#contacts">{{ $c['hero']['cta'] }}</a>
            </div></div>
            @break
        @case('about')
            <section><div class="in"><h2>{{ $c['about']['title'] }}</h2><p>{{ $c['about']['text'] }}</p></div></section>
            @break
        @case('features')
            <section><div class="in"><h2>{{ $c['features']['title'] }}</h2><div class="cards">
                @foreach ($c['features']['items'] as $i)<div class="card"><h3>{{ $i['title'] }}</h3><p>{{ $i['text'] }}</p></div>@endforeach
            </div></div></section>
            @break
        @case('services')
            <section><div class="in"><h2>{{ $c['services']['title'] }}</h2><div class="cards">
                @foreach ($c['services']['items'] as $i)<div class="card"><h3>{{ $i['title'] }}</h3><p>{{ $i['text'] }}</p><div class="price">{{ $i['price'] ?? '' }}</div></div>@endforeach
            </div></div></section>
            @break
        @case('testimonials')
            <section><div class="in"><h2>{{ $c['testimonials']['title'] }}</h2><div class="cards">
                @foreach ($c['testimonials']['items'] as $i)<div class="card"><p class="quote">«{{ $i['text'] }}»</p><strong>{{ $i['title'] }}</strong></div>@endforeach
            </div></div></section>
            @break
        @case('contacts')
            <section id="contacts"><div class="in"><h2>{{ $c['contacts']['title'] }}</h2>
                <p>{{ $c['contacts']['address'] }}<br>{{ $c['contacts']['phone'] }}<br>{{ $c['contacts']['email'] }}</p>
            </div></section>
            @break
    @endswitch
@endforeach
<footer>© {{ date('Y') }} {{ $site->title }}</footer>
</body>
</html>
