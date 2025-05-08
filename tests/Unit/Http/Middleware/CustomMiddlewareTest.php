<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Middleware;

use App\Interfaces\MiddlewareInterface;
use Nette\Application\Request;
use Nette\Application\Responses\TextResponse;
use Nette\DI\Container;
use PHPUnit\Framework\TestCase;

/**
 * Example of a custom middleware that returns a response instead of throwing an exception
 */
class CustomMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, Container $container): ?TextResponse
    {
        // Example middleware that returns a response for a specific condition
        if ($request->getParameter('test') === 'true') {
            return new TextResponse('Custom middleware response');
        }

        // Return null to continue to the next middleware or presenter
        return null;
    }
}

class CustomMiddlewareTest extends TestCase
{
    /**
     * Tests that the CustomMiddleware::handle() method returns a TextResponse when the 'test' parameter is 'true'.
     */
    public function test_handle_returns_response_for_test_parameter(): void
    {
        // Arrange
        $middleware = new CustomMiddleware();
        $request = new Request('Home', 'GET', ['action' => 'deny', 'test' => 'true']);
        $container = $this->createMock(Container::class);

        // Act
        $response = $middleware->handle($request, $container);

        // Assert
        $this->assertInstanceOf(TextResponse::class, $response);
        $this->assertEquals('Custom middleware response', $response->getSource());
    }

    /**
     * Tests that the `handle` method of the `CustomMiddleware` class returns `null`
     * when the provided request contains a non-test parameter.
     */
    public function test_handle_returns_null_for_non_test_parameter(): void
    {
        // Arrange
        $middleware = new CustomMiddleware();
        $request = new Request('Home', 'GET', ['action' => 'deny']);
        $container = $this->createMock(Container::class);

        // Act
        $response = $middleware->handle($request, $container);

        // Assert
        $this->assertNull($response);
    }
}
