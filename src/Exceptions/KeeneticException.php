<?php

declare(strict_types=1);

namespace PhpClient\Keenetic\Exceptions;

use Exception;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Throwable;

class KeeneticException extends Exception
{
    public function __construct(
        string $message = "",
        int $code = 0,
        ?Throwable $previous = null,
        protected readonly null|Request $request = null,
        protected readonly null|Response $response = null,
    ) {
        parent::__construct(message: $message, code: $code, previous: $previous);
    }

}
