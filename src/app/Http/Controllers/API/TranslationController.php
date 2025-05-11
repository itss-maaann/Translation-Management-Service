<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTranslationRequest;
use App\Http\Requests\UpdateTranslationRequest;
use App\Http\Resources\TranslationResource;
use App\Services\TranslationService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class TranslationController extends Controller
{
    use ApiResponse;

    public function __construct(
        private TranslationService $service
    ) {}

    /**
     * Display a paginated list of translations.
     */
    public function index(): JsonResponse
    {
        $paginated = $this->service->list(
            perPage: (int) request('per_page', 15)
        );

        return $this->success(
            data: TranslationResource::collection($paginated)->response()->getData(true)
        );
    }

    /**
     * Store a newly created translation.
     */
    public function store(StoreTranslationRequest $request): JsonResponse
    {
        $translation = $this->service->create($request->validated());

        return $this->success(
            data: new TranslationResource($translation),
            message: 'Translation created successfully.',
            statusCode: 201
        );
    }

    /**
     * Display the specified translation.
     */
    public function show(int $id): JsonResponse
    {
        $translation = $this->service->getById($id);

        return $this->success(
            data: new TranslationResource($translation)
        );
    }

    /**
     * Update the specified translation.
     */
    public function update(
        UpdateTranslationRequest $request,
        int $id
    ): JsonResponse {
        $translation = $this->service->getById($id);
        $updated     = $this->service->update(
            $translation,
            $request->validated()
        );

        return $this->success(
            data: new TranslationResource($updated),
            message: 'Translation updated successfully.'
        );
    }

    /**
     * Remove the specified translation.
     */
    public function destroy(int $id): JsonResponse
    {
        $translation = $this->service->getById($id);
        $this->service->delete($translation);

        return $this->success(
            message: 'Translation deleted successfully.',
            statusCode: 204
        );
    }

    /**
     * Search translations by term.
     */
    public function search(): JsonResponse
    {
        $term      = request('q', '');
        $paginated = $this->service->search(
            term: $term,
            perPage: (int) request('per_page', 15)
        );

        return $this->success(
            data: TranslationResource::collection($paginated)->response()->getData(true)
        );
    }

    /**
     * Export translations as JSON.
     */
    public function export(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        return $this->service->export();
    }
}
