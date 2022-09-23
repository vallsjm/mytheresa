<?php

declare(strict_types=1);

namespace Core\Domain\Filter;

interface ProductFilterInterface
{
    public function category(): string|null;

    public function priceLessThan(): int|null;
}
