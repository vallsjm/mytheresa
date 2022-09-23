<?php

declare(strict_types=1);

namespace Core\Application\UseCase;

use Core\Domain\Request\AddProductRequest;

final class CalculateProductDiscountUseCase
{
    public function __construct(
        private array $discountRules
    ) {}

    public function execute(AddProductRequest $addProductRequest): int|null
    {
        $discount = null;
        foreach ($this->discountRules as $discountRule) {
            $discount = max($discount, $discountRule->percent($addProductRequest));
        }

        return $discount;
    }
}