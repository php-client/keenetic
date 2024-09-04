<?php

declare(strict_types=1);

namespace PhpClient\Keenetic\Requests\Devices;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class ListDevicesRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/rci/show/ip/hotspot';
    }
}
