<?php

declare(strict_types=1);

namespace App\Core;

use App\Http\Middleware\DenyMiddleware;
use Nette;
use Nette\Application\Routers\RouteList;


final class RouterFactory
{
    use Nette\StaticClass;

    public static function createRouter(): RouteList
    {
        $router = new RouteList;

        // Middleware denies this route
        $router->addRoute(mask: '/deny', metadata: [
            'presenter' => 'Home',
            'action' => 'deny',
            'middleware' => fn() => [DenyMiddleware::class],
        ]);

        // Denied page
        $router->addRoute(mask: '/denied-page', metadata: [
            'presenter' => 'Home',
            'action' => 'deniedPage',
        ]);

        // Default route
        $router->addRoute(
            mask: '<presenter>/<action>[/<id>]',
            metadata: [
                'presenter' => 'Home',
                'action' => 'default',
            ],
        );

        return $router;
    }
}
