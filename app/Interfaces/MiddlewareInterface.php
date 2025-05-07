<?php
declare(strict_types=1);

namespace App\Interfaces;

use Nette\Application\Request;
use Nette\Application\Response;
use Nette\DI\Container;

interface MiddlewareInterface
{
    public function handle(Request $request, Container $container): ?Response;
}