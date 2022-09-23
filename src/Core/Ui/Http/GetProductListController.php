<?php

declare(strict_types=1);

namespace Core\Ui\Http;

use Core\Application\UseCase\GetProductListUseCase;
use Core\Domain\Filter\ProductFilter;
use Shared\Infrastructure\Filter\Paginate;
use Shared\Infrastructure\Service\ResponseService;
use Shared\Infrastructure\Hateoas\Representation;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

final class GetProductListController
{
    public function __construct(
        private GetProductListUseCase $getProductListUseCase,
        private ResponseService $responseService
    ) {}

    public function execute(Request $request): Response
    {
        try {
            $productFilter = ProductFilter::createFromArray($request->query->all());
            $paginate = Paginate::createFromArray($request->query->all());

            $products = $this->getProductListUseCase->execute(
                $productFilter,
                $paginate
            );

            $hateoasRepresentation = Representation::create(
                $request,
                $products,
                $paginate
            );
        } catch (\Throwable $e) {
            return $this->responseService->response($e);
        }

        return $this->responseService->response($hateoasRepresentation);
    }
}