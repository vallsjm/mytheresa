<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Tests\Behat\Context;

use Webmozart\Assert\Assert;

final class RestErrorContext extends BaseContext
{
    /**
     * @Then the response should be an error with :errorCode app code, message :errorMessage and :httpStatusCode HTTP status
     */
    public function theJsonResponseShouldContainErrorMessage(string $errorCode, string $errorMessage, int $httpStatusCode): void
    {
        $jsonResponse = $this->getJsonDecodedResponse();

        Assert::keyExists(
            $jsonResponse,
            'error',
            '"error" key expecpted to be in the response, current content: '.print_r($jsonResponse, true)
        );

        Assert::keyExists(
            $jsonResponse['error'],
            'message',
            '"message" key expecpted to be in the response, current content: '.print_r($jsonResponse, true)
        );

        Assert::eq(
            $jsonResponse['error']['message'],
            $errorMessage,
            "Error message expected to be {$errorMessage}, it's ".$jsonResponse['error']['message']
        );

        Assert::keyExists(
            $jsonResponse['error'],
            'app_code',
            '"app_code" key expecpted to be in the error response, current content: '.print_r($jsonResponse['error'], true)
        );

        Assert::eq(
            $errorCode,
            $jsonResponse['error']['app_code'],
            "APP_CODE expected to be {$errorCode}, it's ".$jsonResponse['error']['app_code']
        );

        Assert::eq(
            $this->getSession()->getStatusCode(),
            $httpStatusCode,
            "HTTP status expected to be {$httpStatusCode}, it's ".$this->getSession()->getStatusCode()
        );
    }
}
