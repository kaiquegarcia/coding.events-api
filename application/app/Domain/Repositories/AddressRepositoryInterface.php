<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Address;
use Illuminate\Support\Collection;

interface AddressRepositoryInterface
{
    public function all(array $params = []): Collection;

    public function find(string $id): ?Address;

    public function save(Address $address): ?Address;

    public function delete(string $id): bool;

    public function restore(string $id): bool;
}
