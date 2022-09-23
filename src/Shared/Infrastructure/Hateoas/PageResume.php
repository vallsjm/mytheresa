<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Hateoas;

use Shared\Infrastructure\Filter\Paginate;
use Shared\Infrastructure\Filter\FilterResponse;

class PageResume
{
    private function __construct(
        public int $page,
        public int $size,
        public int $totalPages,
        public int $totalElements
    ) {}

    public static function create(
        FilterResponse $filterResponse,
        Paginate $paginate
    ): self {
        return new self(
            $paginate->page(),
            $paginate->size(),
            (int) ceil($filterResponse->count() / $paginate->size()),
            $filterResponse->count()
        );
    }

    public function page(): int
    {
        return $this->page;
    }

    public function size(): int
    {
        return $this->size;
    }

    public function totalPages(): int
    {
        return $this->totalPages;
    }

    public function totalElements(): int
    {
        return $this->totalElements;
    }
}