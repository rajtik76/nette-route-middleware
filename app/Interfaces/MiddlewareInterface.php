<?php
declare(strict_types=1);

namespace App\Interfaces;

use Nette\Application\Request;
use Nette\Application\Response;

interface MiddlewareInterface
{
    public function handle(Request $request): ?Response;
}