<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\User;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    public function all(array $params = []): Collection;

    public function find(string $id): ?User;

    public function save(User $user): ?User;

    public function delete(string $id): bool;

    public function restore(string $id): bool;
}
