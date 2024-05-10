<?php

declare(strict_types=1);

namespace PhpClient\KeeneticRouter\Responses;

use PhpClient\KeeneticRouter\Resources\Device;
use PhpClient\Support\ValueObjects\Hostname;
use PhpClient\Support\ValueObjects\IpAddressV4;
use PhpClient\Support\ValueObjects\MacAddress;
use stdClass;

class ShoIpHotspotResponse extends Response
{
    /**
     * @return list<Device>
     */
    public function listDevices(): array
    {
        $data = json_decode(
            json: $this->response->getBody()->getContents()
        );

        return array_map(
            callback: static fn(stdClass $item): Device => new Device(
                mac: new MacAddress(value: $item->mac),
                name: $item->name,
                static: $item->dhcp?->static ?? false,
                registered: $item->registered,
                access: $item->access === 'permit',
                online: $item->link === 'up',
                ip: new IpAddressV4(value: $item->ip),
                hostname: $item->hostname ? new Hostname(value: $item->hostname) : null,
            ),
            array: $data->host,
        );
    }
}
