<?php

declare(strict_types=1);

namespace PhpClient\Keenetic;

use PhpClient\Keenetic\Dto\DeviceCollection;
use PhpClient\Keenetic\Requests\GetShowIpHotspotRequest;
use PhpClient\Keenetic\Requests\PostIpDhcpHostNoRequest;
use PhpClient\Keenetic\Requests\PostIpDhcpHostRequest;
use PhpClient\Keenetic\Requests\PostKnownHostNoRequest;
use PhpClient\Keenetic\Requests\PostKnownHostRequest;
use PhpClient\Support\ValueObjects\IpAddressV4;
use PhpClient\Support\ValueObjects\MacAddress;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

final readonly class CommonActions
{
    public function __construct(
        private KeeneticClient $client,
    ) {
    }

    /**
     * @throws FatalRequestException|RequestException
     */
    public function listDevices(): DeviceCollection
    {
        $request = new GetShowIpHotspotRequest();
        $response = $this->client->send(request: $request);

        return $response->dto();
    }

    /**
     * @throws FatalRequestException|RequestException
     */
    public function registerDevice(MacAddress $mac, string $name): bool
    {
        $request = new PostKnownHostRequest(mac: $mac, name: $name);
        $response = $this->client->send(request: $request);

        return $response->successful();
    }

    /**
     * @throws FatalRequestException|RequestException
     */
    public function unregisterDevice(MacAddress $mac): bool
    {
        $request = new PostKnownHostNoRequest(mac: $mac);
        $response = $this->client->send(request: $request);

        return $response->successful();
    }

    /**
     * @throws FatalRequestException|RequestException
     */
    public function setIpForDevice(MacAddress $mac, IpAddressV4 $ip): bool
    {
        $request = new PostIpDhcpHostRequest(mac: $mac, ip: $ip);
        $response = $this->client->send(request: $request);

        return $response->successful();
    }

    /**
     * @throws FatalRequestException|RequestException
     */
    public function unsetIpForDevice(MacAddress $mac): bool
    {
        $request = new PostIpDhcpHostNoRequest(mac: $mac);
        $response = $this->client->send(request: $request);

        return $response->successful();
    }
}
