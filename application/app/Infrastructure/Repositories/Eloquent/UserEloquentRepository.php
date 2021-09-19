<?php

namespace App\Infrastructure\Repositories\Eloquent;

use App\Domain\Entities\User;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Infrastructure\EntityModels\UserModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\Pure;

class UserEloquentRepository extends AbstractEloquentRepository implements UserRepositoryInterface
{

    #[Pure]
    public function __construct(UserModel $model)
    {
        parent::__construct($model);
    }

    protected function prepareEloquentBuilder(array $params): Builder
    {
        $query = $this->model::query();
        $possibleParams = [
            'first_name',
            'last_name',
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

    public function find(string $id): ?User
    {
        return parent::findEntity($id);
    }

    public function save(User $entity): ?User
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
