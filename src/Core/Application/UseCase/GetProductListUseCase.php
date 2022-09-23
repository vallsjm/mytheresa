<?php

declare(strict_types=1);

namespace Core\Application\UseCase;

use Core\Domain\Repository\ProductRepositoryInterface;
use Core\Domain\Filter\ProductFilterInterface;
use Shared\Infrastructure\Filter\PaginateInterface;
use Shared\Infrastructure\Filter\FilterResponse;

final class GetProductListUseCase
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {}

    public function execute(
        ProductFilterInterface $productFilter,
        PaginateInterface|null $paginate = null
    ): FilterResponse
    {
        $paginator = $this->productRepository->filter(
            $productFilter,
            $paginate
        );

        return FilterResponse::create(
            $paginator->count(),
            $paginator->getIterator()
        );
    }
}