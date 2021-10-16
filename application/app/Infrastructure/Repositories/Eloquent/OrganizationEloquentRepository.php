<?php

namespace App\Infrastructure\Repositories\Eloquent;

use App\Domain\Entities\Organization;
use App\Domain\Entities\User;
use App\Domain\Repositories\OrganizationRepositoryInterface;
use App\Infrastructure\EntityModels\OrganizationModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\Pure;

class OrganizationEloquentRepository extends AbstractEloquentRepository implements OrganizationRepositoryInterface
{

    #[Pure]
    public function __construct(
        OrganizationModel $model,
        private UserEloquentRepository $userRepository
    ) {
        parent::__construct($model);
    }

    protected function prepareEloquentBuilder(array $params): Builder
    {
        $query = $this->model::query();
        $possibleParams = [
            'owner_id',
            'corporate_name',
            'fantasy_name',
            'website',
            'status',
        ];
        foreach ($possibleParams as $key) {
            if (empty($params[$key])) {
                continue;
            }
            $query = $query->where($key, $params[$key]);
        }
        return $query;
    }

    public function all(array $params = []): Collection
    {
        return parent::searchEntities($params);
    }

    public function find(string $id): ?Organization
    {
        return parent::findEntity($id);
    }

    public function findOwnerFrom(Organization $organization): ?User
    {
        if (!$organization->getOwnerId()) {
            return null;
        }
        return $this->userRepository->find($organization->getOwnerId());
    }

    public function save(Organization $entity): Organization
    {
        return parent::saveEntity($entity);
    }

    public function delete(string $id): bool
    {
        return parent::deleteById($id);
    }

    public function restore(string $id): bool
    {
        return parent::restoreById($id);
    }
}
