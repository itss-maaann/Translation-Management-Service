<?php

declare(strict_types=1);

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *   schema="Translation",
 *   title="Translation",
 *   @OA\Property(property="id", type="integer"),
 *   @OA\Property(property="locale", type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="code", type="string")
 *   ),
 *   @OA\Property(property="key", type="string"),
 *   @OA\Property(property="value", type="string"),
 *   @OA\Property(property="tags", type="array", @OA\Items(
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string")
 *   )),
 *   @OA\Property(property="created_at", type="string", format="date-time"),
 *   @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *   schema="TranslationsPaginated",
 *   @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Translation")),
 *   @OA\Property(property="current_page", type="integer"),
 *   @OA\Property(property="last_page", type="integer")
 * )
 *
 * @OA\Schema(
 *   schema="Locale",
 *   title="Locale",
 *   @OA\Property(property="id", type="integer"),
 *   @OA\Property(property="code", type="string"),
 *   @OA\Property(property="name", type="string")
 * )
 *
 * @OA\Schema(
 *   schema="LocalesPaginated",
 *   @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Locale")),
 *   @OA\Property(property="current_page", type="integer"),
 *   @OA\Property(property="last_page", type="integer")
 * )
 *
 * @OA\Schema(
 *   schema="Tag",
 *   title="Tag",
 *   @OA\Property(property="id", type="integer"),
 *   @OA\Property(property="name", type="string")
 * )
 *
 * @OA\Schema(
 *   schema="TagsPaginated",
 *   @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Tag")),
 *   @OA\Property(property="current_page", type="integer"),
 *   @OA\Property(property="last_page", type="integer")
 * )
 */
class Schemas
{
}
