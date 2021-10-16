<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Organization;
use App\Domain\Entities\User;
use Illuminate\Support\Collection;

interface OrganizationRepositoryInterface
{
    public function all(array $params = []): Collection;

    public function find(string $id): ?Organization;

    public function findOwnerFrom(Organization $organization): ?User;

    public function save(Organization $organization): ?Organization;

    public function delete(string $id): bool;

    public function restore(string $id): bool;
}
