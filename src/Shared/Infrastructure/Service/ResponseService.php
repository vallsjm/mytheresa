<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class ResponseService
{
    public function __construct(
        private SerializerInterface $serializer,
        private array $exceptionMapping
    ) {}

    private function responseException(\Throwable $e): Response
    {
        $code = $e->getCode();
        foreach ($this->exceptionMapping as $exception) {
            if ($e instanceof $exception['class']) {
                $code = (int) $exception['code'];
            }
        }

        $data = [
            'message' => $e->getMessage(),
            'code' => $code
        ];

        $response = new Response(
            json_encode($data),
            $code
        );

        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode($code);

        return $response;
    }

    public function response(
        $obj,
        array $context = [],
        int $code = Response::HTTP_OK
    ): Response
    {
        if ($obj instanceof \Throwable) {
            return $this->responseException($obj);
        }

        $data = $this->serializer->serialize($obj, 'json', $context);

        $response = new Response(
            $data,
            $code
        );

        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode($code);

        return $response;
    }
}