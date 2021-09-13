<?php

namespace App\Infrastructure\Builders;

class PaginationQueryBuilder
{
    public function __construct(
        private int $page,
        private int $per_page,
        private ?int $total = null,
        private ?int $first_page = null
    ) {
        if (is_null($this->first_page)) {
            $this->first_page = config('application.pagination.first_page');
        }
    }

    public function setTotalElementsCount(int $total): void
    {
        $this->total = $total;
    }

    public function getCurrentPage(): int
    {
        return $this->page;
    }

    public function getCurrentOffset(): int
    {
        return $this->page * $this->per_page;
    }

    public function getCountOfElementsPerPage(): int
    {
        return $this->per_page;
    }

    public function getCountOfElementsAvailable(): int
    {
        return $this->total;
    }

    public function getLastPage(): int
    {
        if ($this->total <= 0 || $this->per_page <= 0) {
            return 0;
        }
        return ceil($this->total / $this->per_page) + ($this->first_page - 1);
    }
}
