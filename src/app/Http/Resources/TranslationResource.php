<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TranslationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'       => $this->id,
            'locale'   => [
                'id'   => $this->locale->id,
                'code' => $this->locale->code,
            ],
            'key'      => $this->key,
            'value'    => $this->value,
            'tags'     => $this->tags->map(fn($tag) => ['id' => $tag->id, 'name' => $tag->name]),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}
