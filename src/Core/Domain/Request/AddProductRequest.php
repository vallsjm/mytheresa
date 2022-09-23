<?php

declare(strict_types=1);

namespace Core\Domain\Request;

class AddProductRequest
{
    private function __construct(
        private string $sku,
        private string $name,
        private string $category,
        private int $price
    ) {}

    public static function create(
        string $sku,
        string $name,
        string $category,
        int $price
    ):self {
        return new self(
            $sku,
            $name,
            $category,
            $price
        );
    }

    public static function createFromArray(array $data): self
    {
        return self::create(
            $data['sku'],
            $data['name'],
            $data['category'],
            $data['price']
        );
    }

    public function price(): int
    {
        return $this->price;
    }

    public function category(): string
    {
        return $this->category;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function sku(): string
    {
        return $this->sku;
    }
}