<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Tests\Behat\Context;

use Behat\Gherkin\Node\PyStringNode;
use Shared\Infrastructure\Tests\Behat\Assert\Assert;

final class HateoasContext extends BaseContext
{
    /**
     * @Then the response must be a HATEAOS paginated response with the page index in :page, :limit records per page, :totalPages total pages and contain these items:
     *
     * @param mixed $page
     * @param mixed $limit
     * @param mixed $totalPages
     */
    public function theHateoasPaginatedResponseShouldContainsPageLimitPagesAndItems($page, $limit, $totalPages, PyStringNode $expectedJson): void
    {
        $currentArrayResponse = json_decode($this->getResponseContent(), true);
        if (null === $currentArrayResponse) {
            throw new \RuntimeException("Can not convert current response to json:\n".$this->getResponseContent());
        }

        $expectedArrayResponse = json_decode($expectedJson->getRaw(), true);
        if (null === $expectedArrayResponse) {
            throw new \RuntimeException("Can not convert expected response to json:\n".$expectedJson->getRaw());
        }

        $this->responseMustBeValid($currentArrayResponse);
        $this->responseShouldContainsPageAndLimitAndPages($page, $limit, $totalPages, $currentArrayResponse);
        $this->responseShouldContainsItems($currentArrayResponse, $expectedArrayResponse);
    }

    private function responseMustBeValid(array $currentArrayResponse): void
    {
        Assert::keyExists(
            $currentArrayResponse,
            'resume',
            sprintf('The HATEOAS response must contain the parameter %s', 'resume')
        );
        Assert::keyExists(
            $currentArrayResponse,
            '_links',
            sprintf('The HATEOAS response must contain the parameter %s', '_links')
        );
        Assert::keyExists(
            $currentArrayResponse['_links'],
            'self',
            sprintf('The HATEOAS response must contain the parameter %s', 'self')
        );
        Assert::keyExists(
            $currentArrayResponse['_links'],
            'first',
            sprintf('The HATEOAS response must contain the parameter %s', 'first')
        );
        Assert::keyExists(
            $currentArrayResponse['_links'],
            'last',
            sprintf('The HATEOAS response must contain the parameter %s', 'last')
        );
        Assert::keyExists(
            $currentArrayResponse,
            'response',
            sprintf('The HATEOAS response must contain the parameter %s', 'response')
        );
    }

    private function responseShouldContainsItems(array $currentArrayResponse, array $expectedArrayResponse): void
    {
        Assert::arrayResponseContains('JSON', $currentArrayResponse['response']['items'], $expectedArrayResponse);
    }

    private function responseShouldContainsPageAndLimitAndPages($page, $limit, $totalPages, array $currentArrayResponse): void
    {
        Assert::eq(
            $currentArrayResponse['resume']['page'],
            $page,
            sprintf('The HATEOAS response should contain the page parameter with value %s, current value %s', $page, $currentArrayResponse['resume']['page'])
        );

        Assert::eq(
            $currentArrayResponse['resume']['size'],
            $limit,
            sprintf('The HATEOAS response should contain the size parameter with value %s, current value %s', $limit, $currentArrayResponse['resume']['size'])
        );
    }
}
