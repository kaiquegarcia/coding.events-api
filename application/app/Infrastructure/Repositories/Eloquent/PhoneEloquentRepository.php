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
        if (isset($params['country_code'])) {
            $query = $query->where('country_code', $params['country_code']);
        }
        if (isset($params['area_code'])) {
            $query = $query->where('area_code', $params['area_code']);
        }
        if (isset($params['number'])) {
            $query = $query->where('number', $params['number']);
        }
        if (isset($params['privacy'])) {
            $query = $query->where('privacy', $params['privacy']);
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
