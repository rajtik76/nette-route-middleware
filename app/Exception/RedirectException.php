<?php
declare(strict_types=1);

namespace App\Exception;

use Exception;

class RedirectException extends Exception
{
    public function __construct(
        public readonly string $url,
        public readonly int    $httpCode = 302
    )
    {
        parent::__construct("Redirect to $url");
    }
}