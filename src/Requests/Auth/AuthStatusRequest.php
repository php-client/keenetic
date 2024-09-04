<?php

declare(strict_types=1);

namespace PhpClient\Keenetic\Requests\Auth;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class AuthStatusRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/auth';
    }
}
