<?php

declare(strict_types=1);

namespace Core\Application\DiscountRules;

use Core\Domain\Request\AddProductRequest;

final class GetDiscountInProductsWithBootsCategory implements DiscountRuleInterface
{
    public function percent(AddProductRequest $addProductRequest): int|null
    {
        return ($addProductRequest->category() === 'boots') ? 30 : null;
    }
}