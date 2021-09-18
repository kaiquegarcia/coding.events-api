<?php

namespace App\Infrastructure\Repositories\Eloquent;

use App\Domain\Entities\Email;
use App\Domain\Repositories\EmailRepositoryInterface;
use App\Infrastructure\EntityModels\EmailModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\Pure;

class EmailEloquentRepository extends AbstractEloquentRepository implements EmailRepositoryInterface
{

    #[Pure]
    public function __construct(EmailModel $model)
    {
        parent::__construct($model);
    }

    protected function prepareEloquentBuilder(array $params): Builder
    {
        $query = $this->model::query();
        $possibleParams = [
            'username',
            'domain',
            'privacy',
        ];
        foreach($possibleParams as $key) {
            if(empty($params[$key])) {
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

    public function find(string $id): ?Email
    {
        return parent::findEntity($id);
    }

    public function save(Email $entity): ?Email
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
