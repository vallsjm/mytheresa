<?php

declare(strict_types=1);

namespace Core\Ui\Http;

use Core\Application\UseCase\GetProductUseCase;
use Core\Domain\Request\GetProductRequest;
use Shared\Infrastructure\Service\ResponseService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

final class GetProductController
{
    public function __construct(
        private GetProductUseCase $getProductUseCase,
        private ResponseService $responseService
    ) {}

    public function execute(Request $request): Response
    {
        try {
            $productRequest = GetProductRequest::create($request->attributes->get('sku'));
            $product = $this->getProductUseCase->execute($productRequest);
        } catch (\Throwable $e) {
            return $this->responseService->response($e);
        }

        return $this->responseService->response($product);
    }
}