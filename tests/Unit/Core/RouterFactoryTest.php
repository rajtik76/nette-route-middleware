<?php

declare(strict_types=1);

namespace Tests\Unit\Core;

use App\Core\RouterFactory;
use App\Http\Middleware\DenyMiddleware;
use Nette\Application\Routers\RouteList;
use PHPUnit\Framework\TestCase;

class RouterFactoryTest extends TestCase
{
    /**
     * Tests that the RouterFactory::createRouter() method returns a RouteList instance.
     */
    public function test_create_router_returns_route_list(): void
    {
        // Act
        $router = RouterFactory::createRouter();

        // Assert
        $this->assertInstanceOf(RouteList::class, $router);
    }

    /**
     * Tests that the /deny route has middleware configured, specifically the DenyMiddleware.
     */
    public function test_deny_route_has_middleware(): void
    {
        // Arrange
        $router = RouterFactory::createRouter();

        // Act
        // Simulate a request to the /deny route
        $request = new \Nette\Application\Request(
            'Home',
            'GET',
            ['action' => 'deny'],
            [],
            []
        );

        $appRequest = $router->match(new \Nette\Http\Request(
            new \Nette\Http\UrlScript('http://example.com/deny')
        ));

        // Assert
        $this->assertNotNull($appRequest);
        $this->assertEquals('Home', $appRequest['presenter']);
        $this->assertEquals('deny', $appRequest['action']);

        // Check that middleware is set and contains DenyMiddleware
        $middlewareCallback = $appRequest['middleware'];
        $this->assertIsCallable($middlewareCallback);

        $middleware = $middlewareCallback();
        $this->assertIsArray($middleware);
        $this->assertContains(DenyMiddleware::class, $middleware);
    }

    /**
     * Tests that the /denied-page route does not have any middleware configured.
     */
    public function test_denied_page_route_has_no_middleware(): void
    {
        // Arrange
        $router = RouterFactory::createRouter();

        // Act
        $appRequest = $router->match(new \Nette\Http\Request(
            new \Nette\Http\UrlScript('http://example.com/denied-page')
        ));

        // Assert
        $this->assertNotNull($appRequest);
        $this->assertEquals('Home', $appRequest['presenter']);
        $this->assertEquals('deniedPage', $appRequest['action']);

        // Check that middleware is not set
        $this->assertArrayNotHasKey('middleware', $appRequest);
    }
}
