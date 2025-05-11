<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Translation;
use Illuminate\Pagination\LengthAwarePaginator;

class TranslationRepository
{
    public function __construct(private Translation $model) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->with(['locale', 'tags'])
            ->orderBy('id', 'asc')
            ->paginate($perPage);
    }

    public function find(int $id): ?Translation
    {
        return $this->model
            ->with(['locale', 'tags'])
            ->findOrFail($id);
    }

    public function create(array $data): Translation
    {
        return $this->model->create($data);
    }

    public function update(Translation $translation, array $data): Translation
    {
        $translation->update($data);
        return $translation;
    }

    public function delete(Translation $translation): void
    {
        $translation->delete();
    }

    public function search(string $term, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->with(['locale', 'tags'])
            ->where('key', 'like', "%{$term}%")
            ->orWhere('value', 'like', "%{$term}%")
            ->orWhereHas('tags', fn($q) => $q->where('name', 'like', "%{$term}%"))
            ->paginate($perPage);
    }

    public function chunkForExport(int $chunkSize = 1000, callable $callback): void
    {
        $this->model
            ->with(['locale', 'tags'])
            ->orderBy('id')
            ->chunk($chunkSize, $callback);
    }
}
