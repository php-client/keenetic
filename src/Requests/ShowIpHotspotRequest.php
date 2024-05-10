<?php

declare(strict_types=1);

namespace PhpClient\KeeneticRouter\Requests;

use PhpClient\Support\Enums\HttpMethod;

final class ShowIpHotspotRequest extends Request
{
    public function __construct()
    {
        parent::__construct(
            method: HttpMethod::GET,
            uri: 'rci/show/ip/hotspot',
        );
    }
}
