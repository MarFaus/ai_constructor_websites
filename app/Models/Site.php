<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Сайт, созданный конструктором. Содержимое и порядок секций хранятся в JSON. */
class Site extends Model
{
    public const THEMES = [
        'indigo' => 'Индиго',
        'emerald' => 'Изумруд',
        'rose' => 'Роза',
        'amber' => 'Янтарь',
        'slate' => 'Графит',
    ];

    public const FONTS = [
        'sans' => 'Без засечек',
        'serif' => 'С засечками',
        'mono' => 'Моноширинный',
    ];

    public const TEMPLATES = [
        'classic' => 'Классика',
        'split' => 'Две колонки',
        'minimal' => 'Минимализм',
        'bold' => 'Тёмный',
        'cards' => 'Карточки',
    ];

    protected $fillable = [
        'title', 'type', 'city', 'prompt', 'theme', 'font', 'template',
        'variant', 'content', 'sections', 'published', 'slug',
    ];

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'sections' => 'array',
            'published' => 'boolean',
        ];
    }

    /** Секции в порядке показа, только включённые. */
    public function enabledSections(): array
    {
        return array_values(array_filter($this->sections, fn ($s) => $s['enabled']));
    }
}
