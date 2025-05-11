<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTranslationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'locale_id' => ['sometimes', 'exists:locales,id'],
            'key'       => ['sometimes', 'string', 'max:255', 'unique:translations,key,' . $this->route('translation') . ',id,locale_id,' . ($this->locale_id ?? 'translations.locale_id')],
            'value'     => ['sometimes', 'string'],
            'tags'      => ['nullable', 'array'],
            'tags.*'    => ['integer', 'exists:tags,id'],
        ];
    }
}
