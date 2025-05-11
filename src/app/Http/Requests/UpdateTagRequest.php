<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('tag');

        return [
            'name' => ['sometimes', 'string', 'max:50', "unique:tags,name,{$id}"],
        ];
    }
}
