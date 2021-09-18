<?php

namespace App\Infrastructure\Repositories\Eloquent;

use App\Domain\Entities\Phone;
use App\Domain\Repositories\PhoneRepositoryInterface;
use App\Infrastructure\EntityModels\PhoneModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\Pure;

class PhoneEloquentRepository extends AbstractEloquentRepository implements PhoneRepositoryInterface
{

    #[Pure]
    public function __construct(PhoneModel $model)
    {
        parent::__construct($model);
    }

    protected function prepareEloquentBuilder(array $params): Builder
    {
        $query = $this->model::query();
        $possibleParams = [
            'country_code',
            'area_code',
            'number',
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

    public function find(string $id): ?Phone
    {
        return parent::findEntity($id);
    }

    public function save(Phone $entity): ?Phone
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
