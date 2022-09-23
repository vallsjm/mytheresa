<?php

declare(strict_types=1);

namespace Core\Domain\Repository;

use Core\Domain\Entity\Product;

interface ProductRepositoryInterface
{
    public function get(string $sku): Product;

    public function delete(Product $product): void;

    public function save(Product $product): void;
}
