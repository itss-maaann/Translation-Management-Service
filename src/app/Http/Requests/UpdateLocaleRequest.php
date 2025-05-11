<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLocaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('locale');

        return [
            'code' => ['sometimes', 'string', 'max:10', "unique:locales,code,{$id}"],
            'name' => ['sometimes', 'string', 'max:100'],
        ];
    }
}
