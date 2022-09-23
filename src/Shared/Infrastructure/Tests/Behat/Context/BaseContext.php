<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Tests\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\RawMinkContext;

abstract class BaseContext extends RawMinkContext implements Context
{
    protected function getResponseContent(): string
    {
        return $this->getSession()->getPage()->getContent();
    }

    protected function getJsonDecodedResponse(): ?array
    {
        return json_decode($this->getResponseContent(), true);
    }

    protected function getXmlDecodedResponse(): ?array
    {
        $xml = simplexml_load_string(
            $this->getResponseContent(),
            'SimpleXMLElement',
            LIBXML_NOCDATA
        );
        $json = json_encode($xml);

        return json_decode($json, true);
    }

    protected function getResponseStatus(): int
    {
        return $this->getSession()->getStatusCode();
    }
}
