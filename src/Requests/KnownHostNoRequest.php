<?php

declare(strict_types=1);

namespace PhpClient\KeeneticRouter\Requests;

use PhpClient\Support\Enums\HttpMethod;
use PhpClient\Support\ValueObjects\MacAddress;

final class KnownHostNoRequest extends Request
{
    public function __construct(MacAddress $mac)
    {
        parent::__construct(
            method: HttpMethod::POST,
            uri: 'rci/known/host',
            data: ['mac' => $mac->value, 'no' => true],
        );
    }
}
