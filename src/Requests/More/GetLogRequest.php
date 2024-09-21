<?php

declare(strict_types=1);

namespace PhpClient\Keenetic\Requests\More;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class GetLogRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/ci/log';
    }
}
