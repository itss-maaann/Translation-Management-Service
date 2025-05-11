<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;

class EloquentUserRepository
{
    public function __construct(
        private User $model
    ) {}

    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }
}
