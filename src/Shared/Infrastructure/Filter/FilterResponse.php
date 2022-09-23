<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Filter;

use ArrayIterator;

class FilterResponse
{
    private function __construct(
        private int $count,
        public ArrayIterator $items
    ) {}

    public static function create(
        int $count,
        ArrayIterator $items
    ):self {
        return new self(
            $count,
            $items
        );
    }

    public function count(): int
    {
        return $this->count;
    }

    public function items(): ArrayIterator
    {
        return $this->items;
    }

}