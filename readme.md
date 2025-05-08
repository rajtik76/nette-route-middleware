# Nette route middleware with response
I'm coming from Laravel, and I really miss the route middleware concept.   
So I create something similar to Laravel route middleware.

## How it works?
1. Create middleware with [MiddlewareInterface](./app/Interfaces/MiddlewareInterface.php). Throw [RedirectException](./app/Exception/RedirectException.php) if you want to redirect from middleware.
```php
<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use App\Exception\RedirectException;
use App\Interfaces\MiddlewareInterface;
use Nette\Application\Request;
use Nette\Application\Response;
use Nette\DI\Container;

class DenyMiddleware implements MiddlewareInterface
{
    /**
     * Deny access to the page.
     *
     * @throws RedirectException
     */
    public function handle(Request $request, Container $container): ?Response
    {
        throw new RedirectException('/denied-page');
    }
}
```
2. Register middleware in [services.neon](./config/services.neon):
```neon
services:
	- App\Http\Middleware\DenyMiddleware
```
3. In [RouteFactory.php](./app/Core/RouterFactory.php) add a middleware metadata key with Closure as an array of middlewares.
```php
// Middleware denies this route
$router->addRoute(mask: '/deny', metadata: [
    'presenter' => 'Home',
    'action' => 'deny',
    'middleware' => fn() => [DenyMiddleware::class],
]);
```
And that's it. The main magic happens in [Bootstrap.php](./app/Bootstrap.php) in attachMiddlewareHook().

## How to test manually?
Go to `/deny` URL and you will be redirected from middleware [DenyMiddleware](./app/Http/Middleware/DenyMiddleware.php) to `/denied-page`

## How to run PHPUnit tests?
This project includes PHPUnit tests to verify the functionality of the middleware system. To run the tests:

1. Install dependencies (if you haven't already):
```bash
composer install
```

2. Run the PHPUnit tests:
```bash
./vendor/bin/phpunit
```

### Test Structure
- **Unit Tests**: Located in the `tests/Unit` directory
  - `Http/Middleware`: Tests for middleware classes
  - `Exception`: Tests for exception classes
  - `Core`: Tests for core components like RouterFactory

### Creating Your Own Tests
1. Create a new test class in the appropriate directory
2. Extend `PHPUnit\Framework\TestCase`
3. Add test methods that begin with `test`
4. Use assertions to verify expected behavior

Example:
```php
<?php
namespace Tests\Unit\YourNamespace;

use PHPUnit\Framework\TestCase;

class YourTest extends TestCase
{
    public function testSomething(): void
    {
        // Arrange
        $object = new YourClass();

        // Act
        $result = $object->someMethod();

        // Assert
        $this->assertEquals('expected value', $result);
    }
}
```
