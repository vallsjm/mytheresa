<?php

declare(strict_types=1);

namespace Core\Application\UseCase;

use Core\Domain\Repository\ProductRepositoryInterface;
use Core\Domain\Request\AddProductRequest;
use Core\Domain\Entity\Product;
use Core\Domain\Entity\Price;

final class AddProductUseCase
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private CalculateProductDiscountUseCase $calculateProductDiscountUseCase
    ) {}

    public function execute(AddProductRequest $addProductRequest): void
    {
        $discount = $this->calculateProductDiscountUseCase->execute($addProductRequest);

        $price = Price::create(
            $addProductRequest->price(),
            $discount
        );

        $product = Product::create(
            $addProductRequest->sku(),
            $addProductRequest->name(),
            $addProductRequest->category(),
            $price
        );

        $this->productRepository->save($product);
    }
}