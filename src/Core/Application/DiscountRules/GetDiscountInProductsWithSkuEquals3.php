<?php

declare(strict_types=1);

namespace Core\Application\DiscountRules;

use Core\Domain\Request\AddProductRequest;

final class GetDiscountInProductsWithSkuEquals3 implements DiscountRuleInterface
{
    public function percent(AddProductRequest $addProductRequest): int|null
    {
        return ($addProductRequest->sku() === '000003') ? 15 : null;
    }
}