<?php

declare(strict_types=1);

namespace Core\Domain\Request;

class GetProductRequest
{
    private function __construct(
        private string $sku
    ) {}

    public static function create(
        string $sku
    ):self {
        return new self(
            $sku
        );
    }

    public function sku(): string
    {
        return $this->sku;
    }
}