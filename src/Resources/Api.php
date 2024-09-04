<?php

declare(strict_types=1);

namespace PhpClient\Keenetic\Resources;

use Saloon\Http\BaseResource;

final class Api extends BaseResource
{
    public function devices(): DevicesResource
    {
        return new DevicesResource(
            connector: $this->connector,
        );
    }
}
