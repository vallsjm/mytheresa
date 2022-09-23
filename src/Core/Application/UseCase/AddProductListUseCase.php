<?php

declare(strict_types=1);

namespace Core\Application\UseCase;

use Core\Domain\Request\AddProductListRequest;

final class AddProductListUseCase
{
    public function __construct(
        private AddProductUseCase $addProductUseCase
    ) {}

    public function execute(AddProductListRequest $addProductListRequest): void
    {
        foreach ($addProductListRequest->productList() as $addProductRequest) {
            $this->addProductUseCase->execute($addProductRequest);
        }
    }
}