<?php

namespace PhpClient\KeeneticRouter\Payloads;

use PhpClient\Support\ValueObjects\Hostname;
use PhpClient\Support\ValueObjects\IpAddressV4;
use PhpClient\Support\ValueObjects\MacAddress;

readonly class Device
{
    public function __construct(
        public MacAddress $mac,
        public string $name,
        public bool $static,
        public bool $registered,
        public bool $access,
        public bool $online,
        public IpAddressV4|null $ip = null,
        public Hostname|null $hostname = null,
    ) {
    }
}
