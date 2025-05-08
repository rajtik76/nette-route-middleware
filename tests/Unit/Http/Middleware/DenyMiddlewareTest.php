<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Middleware;

use App\Exception\RedirectException;
use App\Http\Middleware\DenyMiddleware;
use Nette\Application\Request;
use Nette\DI\Container;
use PHPUnit\Framework\TestCase;

class DenyMiddlewareTest extends TestCase
{
    /**
     * Tests that the DenyMiddleware::handle() method throws a RedirectException.
     */
    public function test_handle_throws_redirect_exception(): void
    {
        // Arrange
        $middleware = new DenyMiddleware();
        $request = new Request('Home', 'GET', ['action' => 'deny']);
        $container = $this->createMock(Container::class);

        // Assert & Act
        $this->expectException(RedirectException::class);
        $this->expectExceptionMessage('Redirect to /denied-page');

        // This should throw a RedirectException
        $middleware->handle($request, $container);
    }

    /**
     * Tests that the RedirectException thrown by DenyMiddleware has the correct URL and HTTP code.
     */
    public function test_redirect_exception_has_correct_url(): void
    {
        // Arrange
        $middleware = new DenyMiddleware();
        $request = new Request('Home', 'GET', ['action' => 'deny']);
        $container = $this->createMock(Container::class);

        try {
            // Act
            $middleware->handle($request, $container);
            $this->fail('Expected RedirectException was not thrown');
        } catch (RedirectException $exception) {
            // Assert
            $this->assertEquals('/denied-page', $exception->url);
            $this->assertEquals(302, $exception->httpCode); // Default HTTP code
        }
    }
}
