<?php

namespace App\Infrastructure\Repositories\Eloquent;

use App\Domain\Entities\AbstractEntity;
use App\Infrastructure\EntityModels\AbstractModel;
use App\Infrastructure\Traits\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

abstract class AbstractEloquentRepository
{
    use WithPagination;

    abstract protected function prepareEloquentBuilder(array $params): Builder;

    public function __construct(
        protected AbstractModel $model
    ) {}

    protected function searchEntities(array $params): Collection
    {
        $pagination = $this->getPaginationQueryBuilder($params);
        $models = $this->prepareEloquentBuilder($params)
            ->offset($pagination->getCurrentOffset())
            ->limit($pagination->getCountOfElementsPerPage())->get();

        $entities = collect();
        foreach($models as $model) {
            /** @var AbstractModel $model */
            $entities->push($model->getEntity());
        }
        return $entities;
    }

    private function findAsModel(string $id): ?AbstractModel
    {
        $model = $this->model::query()->find($id)?->first();
        if (!$model) {
            return null;
        }
        return $model;
    }

    protected function findEntity(string $id): ?AbstractEntity
    {
        $model = $this->findAsModel($id);
        if (!$model) {
            return null;
        }
        return $model->getEntity();
    }

    protected function saveEntity(AbstractEntity $entity): AbstractEntity
    {
        $modelClass = $this->model::class;
        $model = new $modelClass($entity->jsonSerialize());
        $model->saveOrFail();

        return $model->getEntity();
    }

    protected function deleteById(string $id): bool
    {
        $model = $this->findAsModel($id);
        if (!$model) {
            return false;
        }
        return $model->delete();
    }

    protected function restoreById(string $id): bool
    {
        return $this->model::withTrashed()
            ->find($id)
            ->restore();
    }
}
