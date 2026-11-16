<?php

namespace App\Http\Controllers;

use App\Http\Requests\SiteRequest;
use App\Models\Site;
use App\Services\SiteGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class SiteController extends Controller
{
    public function __construct(private SiteGenerator $generator) {}

    public function index()
    {
        return view('sites.index', [
            'sites' => Site::latest()->get(),
            'types' => SiteGenerator::TYPES,
        ]);
    }

    public function store(SiteRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $site = Site::create([
            'title' => $data['title'],
            'type' => $data['type'],
            'city' => $data['city'] ?? null,
            'prompt' => $data['prompt'],
            'variant' => 0,
            'content' => $this->generator->generate($data['title'], $data['type'], $data['city'] ?? null, $data['prompt']),
            'sections' => $this->generator->defaultSections(),
            'slug' => $this->uniqueSlug($data['title']),
        ]);

        return redirect()->route('sites.edit', $site)->with('status', 'Сайт создан. Проверьте тексты и опубликуйте.');
    }

    public function edit(Site $site)
    {
        return view('sites.edit', [
            'site' => $site,
            'sectionNames' => SiteGenerator::SECTIONS,
        ]);
    }

    public function update(SiteRequest $request, Site $site): RedirectResponse
    {
        $data = $request->validated();

        // Сортируем секции по введённым номерам; при равенстве сохраняется прежний порядок.
        $position = array_flip(array_column($site->sections, 'key'));
        $keys = array_keys(SiteGenerator::SECTIONS);
        usort($keys, fn ($a, $b) => [$data['order'][$a] ?? 99, $position[$a] ?? 0] <=> [$data['order'][$b] ?? 99, $position[$b] ?? 0]);
        $enabled = $data['enabled'] ?? [];

        $site->update([
            'title' => $data['title'],
            'city' => $data['city'] ?? null,
            'theme' => $data['theme'],
            'font' => $data['font'],
            'content' => array_replace_recursive($site->content, $data['content']),
            'sections' => array_map(fn ($k) => ['key' => $k, 'enabled' => isset($enabled[$k])], $keys),
        ]);

        return redirect()->route('sites.edit', $site)->with('status', 'Изменения сохранены.');
    }

    public function regenerate(Site $site): RedirectResponse
    {
        $variant = $site->variant + 1;
        $site->update([
            'variant' => $variant,
            'content' => $this->generator->generate($site->title, $site->type, $site->city, $site->prompt, $variant),
        ]);

        return redirect()->route('sites.edit', $site)->with('status', 'Тексты сгенерированы заново.');
    }

    public function publish(Site $site): RedirectResponse
    {
        $site->update(['published' => ! $site->published]);

        return redirect()->route('sites.edit', $site)->with('status', $site->published ? 'Сайт опубликован.' : 'Сайт снят с публикации.');
    }

    public function destroy(Site $site): RedirectResponse
    {
        $site->delete();

        return redirect()->route('sites.index')->with('status', 'Сайт удалён.');
    }

    public function preview(Site $site)
    {
        return view('site', ['site' => $site]);
    }

    public function show(string $slug)
    {
        $site = Site::where('slug', $slug)->where('published', true)->firstOrFail();

        return view('site', ['site' => $site]);
    }

    public function download(Site $site)
    {
        return response(view('site', ['site' => $site])->render())
            ->header('Content-Type', 'text/html; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="'.$site->slug.'.html"');
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'site';
        $slug = $base;
        $i = 2;
        while (Site::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
