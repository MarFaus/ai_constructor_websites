<?php

namespace Tests\Feature;

use App\Models\Site;
use App\Services\SiteGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteTest extends TestCase
{
    use RefreshDatabase;

    private function make(array $over = []): Site
    {
        $g = new SiteGenerator;
        $data = $over + ['title' => 'Кофейня Лист', 'type' => 'cafe', 'city' => 'Омск', 'prompt' => 'Небольшая кофейня в центре города'];

        return Site::create($data + [
            'content' => $g->generate($data['title'], $data['type'], $data['city'], $data['prompt']),
            'sections' => $g->defaultSections(),
            'slug' => 'kofeynya-list',
        ]);
    }

    public function test_index_page_opens(): void
    {
        $this->get('/')->assertOk()->assertSee('Создать сайт');
    }

    public function test_site_is_created_and_generated(): void
    {
        $r = $this->post('/sites', ['title' => 'Кофейня Лист', 'type' => 'cafe', 'prompt' => 'Небольшая кофейня в центре']);
        $site = Site::firstOrFail();
        $r->assertRedirect(route('sites.edit', $site));
        $this->assertCount(6, $site->sections);
        $this->assertSame('Кофейня Лист', $site->content['hero']['title']);
        $this->assertFalse($site->published);
    }

    public function test_validation_errors(): void
    {
        $this->post('/sites', ['title' => '', 'type' => 'bad', 'prompt' => 'коротко'])
            ->assertSessionHasErrors(['title', 'type', 'prompt']);
        $this->post('/sites', ['title' => 'A', 'type' => 'cafe', 'prompt' => str_repeat('я', 601)])
            ->assertSessionHasErrors('prompt');
        $this->assertSame(0, Site::count());
    }

    public function test_generator_is_deterministic_and_variant_changes_text(): void
    {
        $g = new SiteGenerator;
        $a = $g->generate('X', 'shop', null, 'Магазин одежды', 0);
        $this->assertSame($a, $g->generate('X', 'shop', null, 'Магазин одежды', 0));
        $this->assertNotSame($a, $g->generate('X', 'shop', null, 'Магазин одежды', 1));
    }

    public function test_every_type_generates_all_sections(): void
    {
        foreach (array_keys(SiteGenerator::TYPES) as $type) {
            $c = (new SiteGenerator)->generate('Т', $type, 'Омск', 'Описание проекта');
            foreach (array_keys(SiteGenerator::SECTIONS) as $key) {
                $this->assertArrayHasKey($key, $c, $type);
            }
            $this->assertCount(4, $c['services']['items']);
        }
    }

    public function test_unique_slug(): void
    {
        $p = ['title' => 'Одинаковое', 'type' => 'shop', 'prompt' => 'Описание магазина'];
        $this->post('/sites', $p);
        $this->post('/sites', $p);
        $this->assertSame(['odinakovoe', 'odinakovoe-2'], Site::orderBy('id')->pluck('slug')->all());
    }

    public function test_editor_and_preview_open(): void
    {
        $s = $this->make();
        $this->get(route('sites.edit', $s))->assertOk()->assertSee('Предпросмотр');
        $this->get(route('sites.preview', $s))->assertOk()->assertSee('Кофейня Лист');
    }

    public function test_update_changes_theme_text_and_section_order(): void
    {
        $s = $this->make();
        $order = ['hero' => 1, 'about' => 2, 'features' => 3, 'services' => 4, 'testimonials' => 5, 'contacts' => 6];
        $order = ['hero' => 1, 'contacts' => 2, 'about' => 3, 'features' => 4, 'services' => 5, 'testimonials' => 6]; // контакты наверх
        $this->put(route('sites.update', $s), [
            'title' => 'Новое имя', 'theme' => 'rose', 'font' => 'serif', 'template' => 'bold',
            'content' => ['hero' => ['title' => 'Привет', 'subtitle' => 'Подзаголовок', 'cta' => 'Жми'], 'about' => ['text' => 'Текст']],
            'order' => $order,
            'enabled' => ['hero' => 1, 'contacts' => 1, 'about' => 1],
        ])->assertSessionHasNoErrors();
        $s->refresh();
        $this->assertSame('rose', $s->theme);
        $this->assertSame('Привет', $s->content['hero']['title']);
        $this->assertSame('hero', $s->sections[0]['key']);
        $this->assertSame('contacts', $s->sections[1]['key']);
        $this->assertSame(['hero', 'contacts', 'about'], array_column($s->enabledSections(), 'key'));
        $this->get(route('sites.preview', $s))->assertSee('Привет')->assertDontSee('Отзывы клиентов');
    }

    public function test_update_rejects_bad_theme(): void
    {
        $s = $this->make();
        $this->put(route('sites.update', $s), ['title' => 'A', 'theme' => 'neon', 'font' => 'sans'])
            ->assertSessionHasErrors(['theme', 'content', 'order']);
    }

    public function test_regenerate_changes_variant(): void
    {
        $s = $this->make();
        $this->post(route('sites.regenerate', $s))->assertRedirect();
        $this->assertSame(1, $s->fresh()->variant);
    }

    public function test_publish_toggle_and_public_page(): void
    {
        $s = $this->make();
        $this->get('/s/kofeynya-list')->assertNotFound();
        $this->post(route('sites.publish', $s));
        $this->get('/s/kofeynya-list')->assertOk()->assertSee('Кофейня Лист');
        $this->post(route('sites.publish', $s));
        $this->get('/s/kofeynya-list')->assertNotFound();
    }

    public function test_download_returns_html_file(): void
    {
        $s = $this->make();
        $this->get(route('sites.download', $s))->assertOk()
            ->assertHeader('Content-Disposition', 'attachment; filename="kofeynya-list.html"');
    }

    public function test_html_is_escaped(): void
    {
        $s = $this->make(['title' => '<script>alert(1)</script>']);
        $this->get(route('sites.preview', $s))->assertDontSee('<script>alert(1)</script>', false);
    }

    public function test_delete(): void
    {
        $s = $this->make();
        $this->delete(route('sites.destroy', $s))->assertRedirect(route('sites.index'));
        $this->assertSame(0, Site::count());
    }

    public function test_remote_ai_used_when_key_set_and_fallback_on_error(): void
    {
        config(['ai.key' => 'k']);
        $json = (new SiteGenerator)->generate('X', 'shop', null, 'Описание магазина');
        $json['hero']['title'] = 'От нейросети';
        \Illuminate\Support\Facades\Http::fake(['*' => \Illuminate\Support\Facades\Http::sequence()
            ->push(['choices' => [['message' => ['content' => json_encode($json)]]]])
            ->push('err', 500)]);
        $this->assertSame('От нейросети', (new SiteGenerator)->generate('X', 'shop', null, 'Описание магазина')['hero']['title']);

        $this->assertSame('X', (new SiteGenerator)->generate('X', 'shop', null, 'Описание магазина')['hero']['title']);
    }

    public function test_keywords_add_feature_cards_and_design_is_valid(): void
    {
        $g = new SiteGenerator;
        $this->assertArrayHasKey('delivery', $g->analyze('Кафе с бесплатной доставкой и скидками'));
        $c = $g->generate('X', 'cafe', null, 'Кафе с бесплатной доставкой');
        $this->assertSame('Доставка', $c['features']['items'][0]['title']);
        for ($v = 0; $v < 20; $v++) {
            $d = $g->pickDesign('X', 'cafe', 'Описание кафе', $v);
            $this->assertArrayHasKey($d['template'], Site::TEMPLATES);
            $this->assertArrayHasKey($d['theme'], Site::THEMES);
            $this->assertArrayHasKey($d['font'], Site::FONTS);
        }
    }

    public function test_all_five_templates_render_and_randomize_works(): void
    {
        $s = $this->make();
        foreach (array_keys(Site::TEMPLATES) as $t) {
            $s->update(['template' => $t]);
            $this->get(route('sites.preview', $s))->assertOk()->assertSee('t-'.$t, false);
        }
        $this->post(route('sites.randomize', $s))->assertRedirect();
        $this->assertArrayHasKey($s->fresh()->template, Site::TEMPLATES);
    }

    public function test_regenerate_changes_design_over_variants(): void
    {
        $g = new SiteGenerator;
        $set = [];
        for ($v = 0; $v < 10; $v++) {
            $d = $g->pickDesign('Кафе', 'cafe', 'Описание кафе', $v);
            $set[$d['template']] = 1;
        }
        $this->assertGreaterThan(2, count($set));
    }

    public function test_preview_accepts_unsaved_design_and_create_accepts_template(): void
    {
        $s = $this->make();
        $this->get(route('sites.preview', $s).'?template=bold&theme=rose')->assertSee('t-bold', false);
        $this->assertNotSame('bold', $s->fresh()->template);
        $this->post('/sites', ['title' => 'Своё', 'type' => 'shop', 'prompt' => 'Магазин одежды', 'template' => 'minimal']);
        $this->assertSame('minimal', Site::where('title', 'Своё')->first()->template);
    }

    public function test_ai_chooses_design_and_prompt_contains_user_details(): void
    {
        config(['ai.key' => 'k']);
        $json = (new SiteGenerator)->generate('X', 'shop', null, 'Магазин');
        $json['design'] = ['template' => 'bold', 'theme' => 'rose', 'font' => 'nope'];
        \Illuminate\Support\Facades\Http::fake(['*' => \Illuminate\Support\Facades\Http::response(['choices' => [['message' => ['content' => json_encode($json)]]]])]);
        $b = (new SiteGenerator)->build('X', 'shop', null, 'Мерч по Шедоу Фиенду');
        $this->assertSame('bold', $b['design']['template']);
        $this->assertSame('rose', $b['design']['theme']);
        $this->assertArrayHasKey($b['design']['font'], Site::FONTS);
        $this->assertArrayNotHasKey('design', $b['content']);
        \Illuminate\Support\Facades\Http::assertSent(fn ($r) => str_contains($r['messages'][1]['content'], 'Шедоу Фиенду') && $r['messages'][0]['role'] === 'system');
    }
}
