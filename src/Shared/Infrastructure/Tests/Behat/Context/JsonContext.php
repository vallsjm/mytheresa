<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Tests\Behat\Context;

use Behat\Gherkin\Node\PyStringNode;
use Shared\Infrastructure\Tests\Behat\Assert\Assert;
use Symfony\Component\HttpFoundation\Response;

final class JsonContext extends BaseContext
{
    /**
     * @Then the response should be in JSON and contain:
     */
    public function theJsonResponseShouldContain(PyStringNode $expectedJson): void
    {
        Assert::assertJsonResponseContains($this->getResponseContent(), $expectedJson->getRaw());
    }

    /**
     * @Then the JSON response should be empty
     */
    public function theJsonShouldBeEmpty(): void
    {
        Assert::eq(
            $this->getResponseStatus(),
            Response::HTTP_NO_CONTENT,
            'An empty response should have a 204 status code, current status '.$this->getResponseStatus()
        );

        Assert::null(
            $this->getJsonDecodedResponse(),
            "The response must be empty, current content: \n".print_r($this->getJsonDecodedResponse(), true)
        );
    }
}
