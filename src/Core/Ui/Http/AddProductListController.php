<?php

declare(strict_types=1);

namespace Core\Ui\Http;

use Core\Application\UseCase\AddProductListUseCase;
use Core\Domain\Request\AddProductListRequest;
use Shared\Infrastructure\Service\ResponseService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

final class AddProductListController
{
    public function __construct(
        private AddProductListUseCase $addProductListUseCase,
        private ResponseService $responseService
    ) {}

    public function execute(Request $request): Response
    {
        try {
            $productListRequest = AddProductListRequest::createFromArray($request->toArray());
            $this->addProductListUseCase->execute($productListRequest);
        } catch (\Throwable $e) {
            return $this->responseService->response($e);
        }

        return $this->responseService->response(null);
    }
}