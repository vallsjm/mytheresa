<?php

declare(strict_types=1);

namespace Core\Domain\Filter;

class ProductFilter implements ProductFilterInterface
{
    private function __construct(
        private string|null $category,
        private int|null $priceLessThan
    ) {}

    public static function create(
        string|null $category,
        int|null $priceLessThan
    ):self {
        return new self(
            $category,
            $priceLessThan
        );
    }

    public static function createFromArray(array $data): self
    {
        return self::create(
            $data['category'] ?? null,
            (int) ($data['priceLessThan'] ?? null)
        );
    }


    public function category(): string|null
    {
        return $this->category;
    }

    public function priceLessThan(): int|null
    {
        return $this->priceLessThan;
    }

}