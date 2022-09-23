<?php

declare(strict_types=1);

namespace Core\Domain\Entity;

class Price
{
    private function __construct(
        public int $priceOriginal,
        public int|null $discountPercentage
    ) {}

    public static function create(
        int $priceOriginal,
        int|null $discountPercentage
    ):self {
        return new self(
            $priceOriginal,
            $discountPercentage
        );
    }

    public function priceOriginal(): int
    {
        return $this->priceOriginal;
    }

    public function priceFinal(): int
    {
        return $this->discountPercentage ? ($this->priceOriginal - ($this->priceOriginal * $this->discountPercentage/100)) : $this->priceOriginal;
    }

    public function discountPercentage(): int|null
    {
        return $this->discountPercentage;
    }

    public function currency(): string
    {
        return 'EUR';
    }
}