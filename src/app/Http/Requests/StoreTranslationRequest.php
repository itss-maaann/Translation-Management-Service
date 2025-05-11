<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTranslationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'locale_id' => ['required', 'exists:locales,id'],
            'key'       => ['required', 'string', 'max:255', 'unique:translations,key,NULL,id,locale_id,' . $this->locale_id],
            'value'     => ['required', 'string'],
            'tags'      => ['nullable', 'array'],
            'tags.*'    => ['integer', 'exists:tags,id'],
        ];
    }
}
