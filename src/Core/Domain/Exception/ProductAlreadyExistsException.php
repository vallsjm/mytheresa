<?php

declare(strict_types=1);

namespace Core\Domain\Exception;

final class ProductAlreadyExistsException extends \Exception
{
    public static function create(string $productSku): self
    {
        return new self(
            sprintf("Product with sku '%s' already exists.", $productSku)
        );
    }
}