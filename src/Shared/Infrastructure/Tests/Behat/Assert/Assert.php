<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Tests\Behat\Assert;

use Webmozart\Assert\Assert as BaseAssert;

final class Assert extends BaseAssert
{
    public static function assertJsonResponseContains(string $currentResponse, string $expectedResponse, int $expectedTotalElements = null): void
    {
        $expectedArrayResponse = json_decode($expectedResponse, true);
        if (null === $expectedArrayResponse) {
            throw new \RuntimeException("Can not convert expected response to json:\n".$expectedResponse);
        }

        $currentArrayResponse = json_decode($currentResponse, true);
        if (null === $currentArrayResponse) {
            throw new \RuntimeException("Can not convert current response to json:\n".$currentResponse);
        }

        self::arrayResponseContains('JSON', $currentArrayResponse, $expectedArrayResponse);
    }

    public static function assertXmlResponseContains(string $currentResponse, string $expectedResponse, int $expectedTotalElements = null): void
    {
        $xml = simplexml_load_string($expectedResponse, 'SimpleXMLElement', LIBXML_NOCDATA);
        $json = json_encode($xml);

        $expectedArrayResponse = json_decode($json, true);
        if (null === $expectedArrayResponse) {
            throw new \RuntimeException("Can not convert expected response to xml:\n".$expectedResponse);
        }

        $xml = simplexml_load_string($currentResponse, 'SimpleXMLElement', LIBXML_NOCDATA);
        $json = json_encode($xml);

        $currentArrayResponse = json_decode($json, true);
        if (null === $currentArrayResponse) {
            throw new \RuntimeException("Can not convert current response to xml:\n".$currentResponse);
        }

        self::arrayResponseContains('XML', $currentArrayResponse, $expectedArrayResponse);
    }

    public static function arrayResponseContains(string $format, array $currentArrayResponse, array $expectedArrayResponse, int $expectedTotalElements = null): void
    {
        $actualElementsCount = \count($currentArrayResponse);
        $expectedElementsCount = \count($expectedArrayResponse);

        if ($expectedTotalElements) {
            self::eq(
                $actualElementsCount,
                $expectedTotalElements,
                "The {$format} response must have {$expectedTotalElements} elements, it has {$actualElementsCount}, current response "
                .str_replace('%', '%%', print_r($currentArrayResponse, true))
            );
        } else {
            self::greaterThanEq(
                $actualElementsCount,
                $expectedElementsCount,
                "The {$format} response must have at least {$expectedElementsCount} elements, it has {$actualElementsCount}, current response "
                .str_replace('%', '%%', print_r($currentArrayResponse, true))
            );
        }

        self::arrayContains($currentArrayResponse, $expectedArrayResponse);
    }

    private static function arrayContains(array $current, array $expected): void
    {
        foreach ($expected as $expectedValue) {
            self::inArray(
                $expectedValue,
                $current,
                'Comparing expected '
                .str_replace('%', '%%', print_r($expectedValue, true)).' with current '
                .str_replace('%', '%%', print_r($current, true))
            );
        }
    }
}
