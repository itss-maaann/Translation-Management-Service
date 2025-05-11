<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Http\Resources\TagResource;
use App\Services\TagService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *   name="Tags",
 *   description="Manage translation tags"
 * )
 */
class TagController extends Controller
{
    use ApiResponse;

    public function __construct(private TagService $service) {}

    /**
     * List tags
     *
     * @OA\Get(
     *   path="/api/tags",
     *   tags={"Tags"},
     *   security={{"sanctum":{}}},
     *   @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer"), description="Results per page"),
     *   @OA\Response(response=200, description="List of tags", @OA\JsonContent(ref="#/components/schemas/TagsPaginated"))
     * )
     */
    public function index(): JsonResponse
    {
        $tags = $this->service->list((int) request('per_page', 15));
        return $this->success(
            data: TagResource::collection($tags)->response()->getData(true)
        );
    }

    /**
     * Create a tag
     *
     * @OA\Post(
     *   path="/api/tags",
     *   tags={"Tags"},
     *   security={{"sanctum":{}}},
     *   @OA\RequestBody(required=true, @OA\JsonContent(required={"name"}, @OA\Property(property="name", type="string"))),
     *   @OA\Response(response=201, description="Created", @OA\JsonContent(@OA\Property(property="status", type="string"), @OA\Property(property="message", type="string"), @OA\Property(property="data", ref="#/components/schemas/Tag")))
     * )
     */
    public function store(StoreTagRequest $request): JsonResponse
    {
        $tag = $this->service->create($request->validated());
        return $this->success(
            data: new TagResource($tag),
            message: 'Tag created successfully.',
            statusCode: 201
        );
    }

    /**
     * Display the specified tag
     *
     * @OA\Get(
     *   path="/api/tags/{id}",
     *   tags={"Tags"},
     *   security={{"sanctum":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Single tag", @OA\JsonContent(@OA\Property(property="status", type="string"), @OA\Property(property="message", type="string"), @OA\Property(property="data", ref="#/components/schemas/Tag")))
     * )
     */
    public function show(int $id): JsonResponse
    {
        $tag = $this->service->getById($id);
        return $this->success(data: new TagResource($tag));
    }

    /**
     * Update a tag
     *
     * @OA\Put(
     *   path="/api/tags/{id}",
     *   tags={"Tags"},
     *   security={{"sanctum":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\RequestBody(@OA\JsonContent(@OA\Property(property="name", type="string"))),
     *   @OA\Response(response=200, description="Updated tag", @OA\JsonContent(@OA\Property(property="status", type="string"), @OA\Property(property="message", type="string"), @OA\Property(property="data", ref="#/components/schemas/Tag")))
     * )
     */
    public function update(UpdateTagRequest $request, int $id): JsonResponse
    {
        $tag = $this->service->getById($id);
        $updated = $this->service->update($tag, $request->validated());
        return $this->success(
            data: new TagResource($updated),
            message: 'Tag updated successfully.'
        );
    }

    /**
     * Delete a tag
     *
     * @OA\Delete(
     *   path="/api/tags/{id}",
     *   tags={"Tags"},
     *   security={{"sanctum":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=204, description="No Content")
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $tag = $this->service->getById($id);
        $this->service->delete($tag);
        return $this->success(message: 'Tag deleted successfully.', statusCode: 204);
    }
}
