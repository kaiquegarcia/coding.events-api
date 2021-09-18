<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Document;
use Illuminate\Support\Collection;

interface DocumentRepositoryInterface
{
    public function all(array $params = []): Collection;

    public function find(string $id): ?Document;

    public function save(Document $phone): ?Document;

    public function delete(string $id): bool;

    public function restore(string $id): bool;
}
