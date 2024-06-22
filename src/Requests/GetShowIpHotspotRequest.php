<?php

declare(strict_types=1);

namespace PhpClient\Keenetic\Requests;

use JsonException;
use PhpClient\Keenetic\Dto\Device;
use PhpClient\Keenetic\Dto\DeviceCollection;
use PhpClient\Keenetic\Exceptions\KeeneticException;
use PhpClient\Support\ValueObjects\Hostname;
use PhpClient\Support\ValueObjects\IpAddressV4;
use PhpClient\Support\ValueObjects\MacAddress;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use stdClass;

use function array_map;

final class GetShowIpHotspotRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/rci/show/ip/hotspot';
    }

    /**
     * @throws JsonException|KeeneticException
     */
    public function createDtoFromResponse(Response $response): DeviceCollection
    {
        $devices = array_map(
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
            array: $response->object(key: 'host'),
        );

        return new DeviceCollection(items: $devices);
    }
}
