<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Filter;

interface PaginateInterface
{
    public function size(): int;

    public function page(): int;
}
