<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Translation;
use Illuminate\Support\Facades\Cache;
use App\Repositories\TranslationRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TranslationService
{
    public function __construct(private TranslationRepository $repo) {}

    public function list(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repo->paginate($perPage);
    }

    public function getById(int $id): Translation
    {
        return $this->repo->find($id);
    }

    public function create(array $data): Translation
    {
        $translation = $this->repo->create([
            'locale_id' => $data['locale_id'],
            'key'       => $data['key'],
            'value'     => $data['value'],
        ]);

        if (! empty($data['tags'])) {
            $translation->tags()->sync($data['tags']);
        }

        return $translation->load(['locale', 'tags']);
    }

    public function update(Translation $translation, array $data): Translation
    {
        $updated = $this->repo->update($translation, $data);

        if (array_key_exists('tags', $data)) {
            $updated->tags()->sync($data['tags'] ?? []);
        }

        return $updated->load(['locale', 'tags']);
    }

    public function delete(Translation $translation): void
    {
        $this->repo->delete($translation);
    }

    public function search(string $term, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repo->search($term, $perPage);
    }

    public function export(): StreamedResponse
    {
        $cacheKey = config('translations.export_cache_key');
        $ttl      = config('translations.export_ttl');

        $json = Cache::store('redis')->get($cacheKey);
        if (! $json) {
            $entries = [];
            $this->repo->chunkForExport(1000, function ($translations) use (&$entries): void {
                foreach ($translations as $t) {
                    $entries[] = [
                        'key'    => $t->key,
                        'value'  => $t->value,
                        'locale' => $t->locale->code,
                        'tags'   => $t->tags->pluck('name')->toArray(),
                    ];
                }
            });
            $json = json_encode($entries);
            Cache::store('redis')->put($cacheKey, $json, $ttl);
        }

        $count = is_array($decoded = json_decode($json, true))
            ? count($decoded)
            : 0;

        $callback = function () use ($json, $count): void {
            echo '{"Total Count":' . $count . ',"data":' . $json . '}';
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'application/json',
        ]);
    }
}
