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

## How to test?
go to `/deny` URL and you will be redirected from middleware [DenyMiddleware](./app/Http/Middleware/DenyMiddleware.php) to `/denied-page`
