<?php

declare(strict_types=1);

namespace Core\Domain\Exception;

final class ProductBadRequestException extends \Exception
{
    public static function create(): self
    {
        return new self(
            "Product bad request."
        );
    }
}