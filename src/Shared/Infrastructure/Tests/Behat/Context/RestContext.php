<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Tests\Behat\Context;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\RawMinkContext;
use Symfony\Component\BrowserKit\AbstractBrowser;

class RestContext extends RawMinkContext
{

    /**
     * Sends a HTTP request.
     *
     * @Given I send a :method request to :url
     *
     * @param mixed $method
     * @param mixed $url
     * @param mixed $files
     */
    public function iSendARequestTo($method, $url, PyStringNode $body = null, $files = [])
    {
        return $this->getClient()->request(
            $method,
            $this->locatePath($url),
            [],
            $files,
            [],
            null !== $body ? trim($body->getRaw()) : null
        );
    }

    /**
     * Sends a HTTP request with a body.
     *
     * @Given I send a :method request to :url with body:
     *
     * @param mixed $method
     * @param mixed $url
     */
    public function iSendARequestToWithBody($method, $url, PyStringNode $body)
    {
        return $this->iSendARequestTo($method, $url, $body);
    }

    /**
     * Sends a HTTP request with a some parameters.
     *
     * @Given I send a form :method request to :url with parameters:
     *
     * @param mixed $method
     * @param mixed $url
     */
    public function iSendARequestToWithParameters($method, $url, TableNode $data)
    {
        $files = [];
        $parameters = [];

        foreach ($data->getHash() as $row) {
            if (!isset($row['key']) || !isset($row['value'])) {
                throw new \Exception("You must provide a 'key' and 'value' column in your table node.");
            }

            if (\is_string($row['value']) && '@' === mb_substr($row['value'], 0, 1)) {
                $files[$row['key']] = rtrim($this->getMinkParameter('files_path'), \DIRECTORY_SEPARATOR).\DIRECTORY_SEPARATOR.mb_substr($row['value'], 1);
            } else {
                $matches = [];
                if (preg_match('/([a-z].*)\[([a-z].*)\]/', $row['key'], $matches)) {
                    if (!isset($parameters[$matches[1]])) {
                        $parameters[$matches[1]] = [];
                    }
                    $parameters[$matches[1]][$matches[2]] = $row['value'];
                } else {
                    $parameters[$row['key']] = $row['value'];
                }
            }
        }

        return $this->getClient()->request(
            $method,
            $this->locatePath($url),
            $parameters,
            $files,
            []
        );
    }

    /**
     * @Given I add header :name with :value
     *
     * @param mixed $name
     * @param mixed $value
     */
    public function iAddHeader($name, $value): void
    {
        $this->setHttpHeader($name, $value);
    }

    private function getClient(): AbstractBrowser
    {
        return $this->getMink()->getSession()->getDriver()->getClient();
    }

    private function setHttpHeader($name, $value): void
    {
        $client = $this->getMink()->getSession()->getDriver()->getClient();
        // Goutte\Client
        if (method_exists($client, 'setHeader')) {
            $client->setHeader($name, $value);
        } else {
            // Symfony\Component\BrowserKit\Client

            // taken from Behat\Mink\Driver\BrowserKitDriver::setRequestHeader
            $contentHeaders = ['CONTENT_LENGTH' => true, 'CONTENT_MD5' => true, 'CONTENT_TYPE' => true];
            $name = str_replace('-', '_', strtoupper($name));

            // CONTENT_* are not prefixed with HTTP_ in PHP when building $_SERVER
            if (!isset($contentHeaders[$name])) {
                $name = 'HTTP_'.$name;
            }
            // taken from Behat\Mink\Driver\BrowserKitDriver::setRequestHeader
            $client->setServerParameter($name, $value);
        }
    }
}
