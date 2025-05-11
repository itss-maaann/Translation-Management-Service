<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tag;
use App\Repositories\TagRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class TagService
{
    public function __construct(private TagRepository $repo) {}

    public function list(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repo->paginate($perPage);
    }

    public function getById(int $id): Tag
    {
        return $this->repo->find($id);
    }

    public function create(array $data): Tag
    {
        return $this->repo->create($data);
    }

    public function update(Tag $tag, array $data): Tag
    {
        return $this->repo->update($tag, $data);
    }

    public function delete(Tag $tag): void
    {
        $this->repo->delete($tag);
    }
}
