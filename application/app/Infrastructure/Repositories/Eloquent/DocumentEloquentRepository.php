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
        if (isset($params['document_type'])) {
            $query = $query->where('document_type', $params['document_type']);
        }
        if (isset($params['value'])) {
            $query = $query->where('value', $params['value']);
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
