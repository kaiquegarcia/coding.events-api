<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Email;
use Illuminate\Support\Collection;

interface EmailRepositoryInterface
{
    public function all(array $params = []): Collection;

    public function find(string $id): ?Email;

    public function save(Email $email): ?Email;

    public function delete(string $id): bool;

    public function restore(string $id): bool;
}
