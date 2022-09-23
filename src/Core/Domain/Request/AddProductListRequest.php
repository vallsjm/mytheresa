<?php

declare(strict_types=1);

namespace Core\Domain\Request;

class AddProductListRequest
{
    private function __construct(
        private array $productList = []
    ) {}

    public static function create(
        array $productList
    ):self {
        return new self(
            $productList
        );
    }

    public static function createFromArray(array $data): self
    {
        $products = $data['products'];
        foreach ($products as $product) {
            $productList[] = AddProductRequest::createFromArray(
                $product
            );
        }

        return self::create($productList);
    }


    public function productList(): array
    {
        return $this->productList;
    }
}