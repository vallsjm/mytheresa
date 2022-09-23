<?php

declare(strict_types=1);

namespace Core\Infrastructure\Serializer;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Core\Domain\Entity\Price;

class PriceNormalizer implements NormalizerInterface
{
    private ObjectNormalizer $normalizer;

    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function normalize($obj, string $format = null, array $context = [])
    {
        $data = [
            'original' => $obj->priceOriginal(),
            'final' => $obj->priceFinal(),
            'discount_percentage' => $obj->discountPercentage() ? sprintf('%d%%', $obj->discountPercentage()) : null,
            'currency' => $obj->currency()
        ];

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof Price;
    }
}