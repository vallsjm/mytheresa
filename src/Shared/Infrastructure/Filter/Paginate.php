<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Filter;

class Paginate implements PaginateInterface
{
    const DEFAULT_PAGE_SIZE = 10;

    private function __construct(
        private int $page,
        private int $size
    ) {}

    public static function create(
        int $page = 1,
        int $size = self::DEFAULT_PAGE_SIZE
    ):self {
        return new self(
            $page,
            $size
        );
    }

    public static function createFromArray(array $data): self
    {
        return self::create(
            (int) ($data['page'] ?? 1),
            (int) ($data['size'] ?? self::DEFAULT_PAGE_SIZE)
        );
    }

    public function size(): int
    {
        return $this->size;
    }

    public function page(): int
    {
        return $this->page;
    }
}