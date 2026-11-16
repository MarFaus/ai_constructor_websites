<?php

namespace App\Http\Requests;

use App\Models\Site;
use App\Services\SiteGenerator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SiteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:80'],
            'city' => ['nullable', 'string', 'max:60'],
        ];

        if ($this->isMethod('post')) {
            return $rules + [
                'type' => ['required', Rule::in(array_keys(SiteGenerator::TYPES))],
                'template' => ['nullable', Rule::in(array_keys(Site::TEMPLATES))],
                'prompt' => ['required', 'string', 'min:10', 'max:600'],
            ];
        }

        return $rules + [
            'theme' => ['required', Rule::in(array_keys(Site::THEMES))],
            'font' => ['required', Rule::in(array_keys(Site::FONTS))],
            'template' => ['required', Rule::in(array_keys(Site::TEMPLATES))],
            'content' => ['required', 'array'],
            'content.hero.title' => ['required', 'string', 'max:120'],
            'content.hero.subtitle' => ['required', 'string', 'max:200'],
            'content.hero.cta' => ['required', 'string', 'max:40'],
            'content.about.text' => ['required', 'string', 'max:1500'],
            'content.contacts.email' => ['nullable', 'email', 'max:100'],
            'content.*.title' => ['nullable', 'string', 'max:120'],
            'content.*.items.*.title' => ['nullable', 'string', 'max:120'],
            'content.*.items.*.text' => ['nullable', 'string', 'max:300'],
            'content.*.items.*.price' => ['nullable', 'string', 'max:40'],
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'min:1', 'max:99'],
            'enabled' => ['nullable', 'array'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Введите название сайта.',
            'prompt.required' => 'Опишите, какой сайт нужен.',
            'prompt.min' => 'Описание слишком короткое: минимум 10 символов.',
            'prompt.max' => 'Описание слишком длинное: максимум 600 символов.',
            'type.required' => 'Выберите тип сайта.',
            'type.in' => 'Выберите тип из списка.',
            'content.contacts.email.email' => 'Укажите корректный e-mail.',
        ];
    }
}
