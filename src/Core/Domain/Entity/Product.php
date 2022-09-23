<?php

declare(strict_types=1);

namespace Core\Domain\Entity;

class Product
{
    private function __construct(
        public string $sku,
        public string $name,
        public string $category,
        public Price $price
    ) {}

    public static function create(
        string $sku,
        string $name,
        string $category,
        Price $price
    ):self {
        return new self(
            $sku,
            $name,
            $category,
            $price
        );
    }

    public function price(): Price
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