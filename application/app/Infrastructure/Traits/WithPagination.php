<?php

namespace App\Infrastructure\Traits;

use App\Infrastructure\Builders\PaginationQueryBuilder;

trait WithPagination
{
    private function hasPagination(array $params): bool
    {
        return isset($params['page'], $params['per_page']) && intval($params['per_page']) > 0;
    }

    protected function getPaginationQueryBuilder(array $params): PaginationQueryBuilder
    {
        if (!$this->hasPagination($params)) {
            $params['page'] = config('application.pagination.first_page');
            $params['per_page'] = config('application.pagination.default_per_page');
        }
        return new PaginationQueryBuilder(
            page: intval($params['page']),
            per_page: intval($params['per_page'])
        );
    }
}
