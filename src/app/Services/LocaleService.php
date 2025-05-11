<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Locale;
use App\Repositories\LocaleRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class LocaleService
{
    public function __construct(private LocaleRepository $repo) {}

    public function list(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repo->paginate($perPage);
    }

    public function getById(int $id): Locale
    {
        return $this->repo->find($id);
    }

    public function create(array $data): Locale
    {
        return $this->repo->create($data);
    }

    public function update(Locale $locale, array $data): Locale
    {
        return $this->repo->update($locale, $data);
    }

    public function delete(Locale $locale): void
    {
        $this->repo->delete($locale);
    }
}
