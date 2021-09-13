<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Phone;
use Illuminate\Support\Collection;

interface PhoneRepositoryInterface
{
    public function all(array $params = []): Collection;

    public function find(string $id): ?Phone;

    public function save(Phone $phone): ?Phone;

    public function delete(string $id): bool;

    public function restore(string $id): bool;
}
