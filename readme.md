# Nette route middleware with response
I'm coming from Laravel, and I really miss the route middleware concept.   
So I create something similar to Laravel route middleware.

## How it works?
1. Create middleware with [MiddlewareInterface](./app/Interfaces/MiddlewareInterface.php). Throw [RedirectException](./app/Exception/RedirectException.php) if you want to redirect from middleware
2. In [RouteFactory.php](./app/Core/RouterFactory.php) add middleware metadata key with a list of middlewares

And that's it. The main magic happens in [Bootstrap.php](./app/Bootstrap.php) in attachMiddlewareHook().

## How to test?
go to `/deny` URL and you will be redirected from middleware [DenyMiddleware](./app/Http/Middleware/DenyMiddleware.php) to `/denied-page` 