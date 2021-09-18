<?php

namespace App\Infrastructure\Repositories\Eloquent;

use App\Domain\Entities\Address;
use App\Domain\Repositories\AddressRepositoryInterface;
use App\Infrastructure\EntityModels\AddressModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\Pure;

class AddressEloquentRepository extends AbstractEloquentRepository implements AddressRepositoryInterface
{

    #[Pure]
    public function __construct(AddressModel $model)
    {
        parent::__construct($model);
    }

    protected function prepareEloquentBuilder(array $params): Builder
    {
        $query = $this->model::query();
        $possibleParams = [
            'postal_code',
            'country',
            'state',
            'city',
            'neighborhood',
            'street',
            'number',
            'complement',
            'privacy',
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

    public function find(string $id): ?Address
    {
        return parent::findEntity($id);
    }

    public function save(Address $entity): ?Address
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
