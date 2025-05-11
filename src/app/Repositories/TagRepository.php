<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Tag;
use Illuminate\Pagination\LengthAwarePaginator;

class TagRepository
{
    public function __construct(private Tag $model) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->orderBy('id', 'desc')->paginate($perPage);
    }

    public function find(int $id): Tag
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): Tag
    {
        return $this->model->create($data);
    }

    public function update(Tag $tag, array $data): Tag
    {
        $tag->update($data);
        return $tag;
    }

    public function delete(Tag $tag): void
    {
        $tag->delete();
    }
}
