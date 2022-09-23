<?php

declare(strict_types=1);

namespace Core\Application\DiscountRules;

use Core\Domain\Request\AddProductRequest;

interface DiscountRuleInterface
{
    public function percent(AddProductRequest $addProductRequest): int|null;
}
