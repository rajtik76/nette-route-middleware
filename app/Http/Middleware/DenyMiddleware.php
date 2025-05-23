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