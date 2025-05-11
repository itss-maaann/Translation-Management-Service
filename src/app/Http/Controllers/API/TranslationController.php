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
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *   name="Translations",
 *   description="Manage translation entries"
 * )
 */
class TranslationController extends Controller
{
    use ApiResponse;

    public function __construct(private TranslationService $service) {}

    /**
     * List translations
     *
     * @OA\Get(
     *   path="/api/translations",
     *   tags={"Translations"},
     *   security={{"sanctum":{}}},
     *   @OA\Parameter(
     *     name="per_page", in="query", @OA\Schema(type="integer"),
     *     description="Results per page"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="List of translations",
     *     @OA\JsonContent(ref="#/components/schemas/TranslationsPaginated")
     *   )
     * )
     */
    public function index(): JsonResponse
    {
        $paginated = $this->service->list(
            perPage: (int) request('per_page', 15)
        );

        return $this->success(
            data: TranslationResource::collection($paginated)
                      ->response()->getData(true)
        );
    }

    /**
     * Create a translation
     *
     * @OA\Post(
     *   path="/api/translations",
     *   tags={"Translations"},
     *   security={{"sanctum":{}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"locale_id","key","value"},
     *       @OA\Property(property="locale_id", type="integer"),
     *       @OA\Property(property="key", type="string"),
     *       @OA\Property(property="value", type="string"),
     *       @OA\Property(property="tags", type="array", @OA\Items(type="integer"))
     *     )
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="Created",
     *     @OA\JsonContent(
     *       @OA\Property(property="status", type="string"),
     *       @OA\Property(property="message", type="string"),
     *       @OA\Property(
     *         property="data",
     *         ref="#/components/schemas/Translation"
     *       )
     *     )
     *   )
     * )
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
     *
     * @OA\Get(
     *   path="/api/translations/{id}",
     *   tags={"Translations"},
     *   security={{"sanctum":{}}},
     *   @OA\Parameter(
     *     name="id", in="path", required=true, @OA\Schema(type="integer")
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Single translation",
     *     @OA\JsonContent(
     *       @OA\Property(property="status", type="string"),
     *       @OA\Property(property="message", type="string"),
     *       @OA\Property(property="data", ref="#/components/schemas/Translation")
     *     )
     *   )
     * )
     */
    public function show(int $id): JsonResponse
    {
        $translation = $this->service->getById($id);

        return $this->success(
            data: new TranslationResource($translation)
        );
    }

    /**
     * Update a translation
     *
     * @OA\Put(
     *   path="/api/translations/{id}",
     *   tags={"Translations"},
     *   security={{"sanctum":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       @OA\Property(property="locale_id", type="integer"),
     *       @OA\Property(property="key", type="string"),
     *       @OA\Property(property="value", type="string"),
     *       @OA\Property(property="tags", type="array", @OA\Items(type="integer"))
     *     )
     *   ),
     *   @OA\Response(response=200, description="Updated translation",
     *     @OA\JsonContent(
     *       @OA\Property(property="status", type="string"),
     *       @OA\Property(property="message", type="string"),
     *       @OA\Property(property="data", ref="#/components/schemas/Translation")
     *     )
     *   )
     * )
     */
    public function update(UpdateTranslationRequest $request, int $id): JsonResponse
    {
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
     * Delete a translation
     *
     * @OA\Delete(
     *   path="/api/translations/{id}",
     *   tags={"Translations"},
     *   security={{"sanctum":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=204, description="No Content")
     * )
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
     * Search translations
     *
     * @OA\Get(
     *   path="/api/translations/search",
     *   tags={"Translations"},
     *   security={{"sanctum":{}}},
     *   @OA\Parameter(name="q", in="query", @OA\Schema(type="string")),
     *   @OA\Response(response=200, description="Search results",
     *     @OA\JsonContent(ref="#/components/schemas/TranslationsPaginated")
     *   )
     * )
     */
    public function search(): JsonResponse
    {
        $paginated = $this->service->search(
            term: request('q', ''),
            perPage: (int) request('per_page', 15)
        );

        return $this->success(
            data: TranslationResource::collection($paginated)
                      ->response()->getData(true)
        );
    }

    /**
     * Export translations JSON
     *
     * @OA\Get(
     *   path="/api/translations/export",
     *   tags={"Translations"},
     *   security={{"sanctum":{}}},
     *   @OA\Response(response=200, description="Exported translations")
     * )
     */
    public function export(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        return $this->service->export();
    }
}
