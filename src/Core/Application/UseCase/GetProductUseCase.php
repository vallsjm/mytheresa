<?php

declare(strict_types=1);

namespace Core\Application\UseCase;

use Core\Domain\Repository\ProductRepositoryInterface;
use Core\Domain\Request\GetProductRequest;
use Core\Domain\Entity\Product;

final class GetProductUseCase
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {}

    public function execute(GetProductRequest $getProductRequest): Product
    {
        return $this->productRepository->get(
            $getProductRequest->sku()
        );
    }
}