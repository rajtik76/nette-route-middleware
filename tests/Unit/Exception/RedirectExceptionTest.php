<?php

declare(strict_types=1);

namespace Tests\Unit\Exception;

use App\Exception\RedirectException;
use PHPUnit\Framework\TestCase;

class RedirectExceptionTest extends TestCase
{
    /**
     * Tests that the RedirectException constructor correctly sets the url, httpCode, and message properties.
     */
    public function test_constructor_sets_properties(): void
    {
        // Arrange & Act
        $url = '/test-url';
        $httpCode = 301;
        $exception = new RedirectException($url, $httpCode);

        // Assert
        $this->assertEquals($url, $exception->url);
        $this->assertEquals($httpCode, $exception->httpCode);
        $this->assertEquals("Redirect to $url", $exception->getMessage());
    }

    /**
     * Tests that the default HTTP code for RedirectException is 302 when not explicitly provided.
     */
    public function test_default_http_code_is_302(): void
    {
        // Arrange & Act
        $url = '/test-url';
        $exception = new RedirectException($url);

        // Assert
        $this->assertEquals(302, $exception->httpCode);
    }
}
