<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Locale;
use Illuminate\Pagination\LengthAwarePaginator;

class LocaleRepository
{
    public function __construct(private Locale $model) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->orderBy('id', 'desc')->paginate($perPage);
    }

    public function find(int $id): Locale
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): Locale
    {
        return $this->model->create($data);
    }

    public function update(Locale $locale, array $data): Locale
    {
        $locale->update($data);
        return $locale;
    }

    public function delete(Locale $locale): void
    {
        $locale->delete();
    }
}
