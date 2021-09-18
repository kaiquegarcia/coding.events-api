<?php

namespace App\Infrastructure\Repositories\Eloquent;

use App\Domain\Entities\Document;
use App\Domain\Repositories\DocumentRepositoryInterface;
use App\Infrastructure\EntityModels\DocumentModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\Pure;

class DocumentEloquentRepository extends AbstractEloquentRepository implements DocumentRepositoryInterface
{

    #[Pure]
    public function __construct(DocumentModel $model)
    {
        parent::__construct($model);
    }

    protected function prepareEloquentBuilder(array $params): Builder
    {
        $query = $this->model::query();
        $possibleParams = [
            'document_type',
            'value',
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

    public function find(string $id): ?Document
    {
        return parent::findEntity($id);
    }

    public function save(Document $entity): ?Document
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
