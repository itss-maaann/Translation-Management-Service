<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLocaleRequest;
use App\Http\Requests\UpdateLocaleRequest;
use App\Http\Resources\LocaleResource;
use App\Services\LocaleService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *   name="Locales",
 *   description="Manage application locales"
 * )
 */
class LocaleController extends Controller
{
    use ApiResponse;

    public function __construct(private LocaleService $service) {}

    /**
     * List locales
     *
     * @OA\Get(
     *   path="/api/locales",
     *   tags={"Locales"},
     *   security={{"sanctum":{}}},
     *   @OA\Parameter(
     *     name="per_page", in="query", @OA\Schema(type="integer"),
     *     description="Results per page"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="List of locales",
     *     @OA\JsonContent(ref="#/components/schemas/LocalesPaginated")
     *   )
     * )
     */
    public function index(): JsonResponse
    {
        $locales = $this->service->list((int) request('per_page', 15));
        return $this->success(
            data: LocaleResource::collection($locales)->response()->getData(true)
        );
    }

    /**
     * Create a locale
     *
     * @OA\Post(
     *   path="/api/locales",
     *   tags={"Locales"},
     *   security={{"sanctum":{}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"code","name"},
     *       @OA\Property(property="code", type="string"),
     *       @OA\Property(property="name", type="string")
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
     *         ref="#/components/schemas/Locale"
     *       )
     *     )
     *   )
     * )
     */
    public function store(StoreLocaleRequest $request): JsonResponse
    {
        $locale = $this->service->create($request->validated());
        return $this->success(
            data: new LocaleResource($locale),
            message: 'Locale created successfully.',
            statusCode: 201
        );
    }

    /**
     * Display the specified locale
     *
     * @OA\Get(
     *   path="/api/locales/{id}",
     *   tags={"Locales"},
     *   security={{"sanctum":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(
     *     response=200,
     *     description="Single locale",
     *     @OA\JsonContent(
     *       @OA\Property(property="status", type="string"),
     *       @OA\Property(property="message", type="string"),
     *       @OA\Property(property="data", ref="#/components/schemas/Locale")
     *     )
     *   )
     * )
     */
    public function show(int $id): JsonResponse
    {
        $locale = $this->service->getById($id);
        return $this->success(data: new LocaleResource($locale));
    }

    /**
     * Update a locale
     *
     * @OA\Put(
     *   path="/api/locales/{id}",
     *   tags={"Locales"},
     *   security={{"sanctum":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       @OA\Property(property="code", type="string"),
     *       @OA\Property(property="name", type="string")
     *     )
     *   ),
     *   @OA\Response(response=200, description="Updated locale",
     *     @OA\JsonContent(
     *       @OA\Property(property="status", type="string"),
     *       @OA\Property(property="message", type="string"),
     *       @OA\Property(property="data", ref="#/components/schemas/Locale")
     *     )
     *   )
     * )
     */
    public function update(UpdateLocaleRequest $request, int $id): JsonResponse
    {
        $locale = $this->service->getById($id);
        $updated = $this->service->update($locale, $request->validated());
        return $this->success(
            data: new LocaleResource($updated),
            message: 'Locale updated successfully.'
        );
    }

    /**
     * Delete a locale
     *
     * @OA\Delete(
     *   path="/api/locales/{id}",
     *   tags={"Locales"},
     *   security={{"sanctum":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=204, description="No Content")
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $locale = $this->service->getById($id);
        $this->service->delete($locale);
        return $this->success(message: 'Locale deleted successfully.', statusCode: 204);
    }
}
