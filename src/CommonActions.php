<?php

declare(strict_types=1);

namespace PhpClient\KeeneticRouter;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use PhpClient\KeeneticRouter\Resources\Device;
use PhpClient\KeeneticRouter\Requests\IpDhcpHostNoRequest;
use PhpClient\KeeneticRouter\Requests\IpDhcpHostRequest;
use PhpClient\KeeneticRouter\Requests\KnownHostNoRequest;
use PhpClient\KeeneticRouter\Requests\KnownHostRequest;
use PhpClient\KeeneticRouter\Requests\ShowIpHotspotRequest;
use PhpClient\KeeneticRouter\Responses\CommonResponse;
use PhpClient\KeeneticRouter\Responses\ShoIpHotspotResponse;
use PhpClient\Support\ValueObjects\IpAddressV4;
use PhpClient\Support\ValueObjects\MacAddress;

final readonly class CommonActions
{
    public function __construct(
        private KeeneticClient $client
    ) {
    }

    private function isSuccess(Response $response): bool
    {
        $customResponse = new CommonResponse(response: $response);

        return $customResponse->isSuccess();
    }

    /**
     * @return list<Device>
     * @throws GuzzleException
     */
    public function listDevices(): array
    {
        $request = new ShowIpHotspotRequest();
        $response = $this->client->send(request: $request);
        $customResponse = new ShoIpHotspotResponse(response: $response);

        return $customResponse->listDevices();
    }

    /**
     * @throws GuzzleException
     */
    public function registerDevice(MacAddress $mac, string $name): bool
    {
        $request = new KnownHostRequest(mac: $mac, name: $name);
        $response = $this->client->send(request: $request);

        return $this->isSuccess($response);
    }

    /**
     * @throws GuzzleException
     */
    public function unregisterDevice(MacAddress $mac): bool
    {
        $request = new KnownHostNoRequest(mac: $mac);
        $response = $this->client->send(request: $request);

        return $this->isSuccess($response);
    }

    /**
     * @throws GuzzleException
     */
    public function setIpForDevice(MacAddress $mac, IpAddressV4 $ip): bool
    {
        $request = new IpDhcpHostRequest(mac: $mac, ip: $ip);
        $response = $this->client->send(request: $request);

        return $this->isSuccess($response);
    }

    /**
     * @throws GuzzleException
     */
    public function unsetIpForDevice(MacAddress $mac): bool
    {
        $request = new IpDhcpHostNoRequest(mac: $mac);
        $response = $this->client->send(request: $request);

        return $this->isSuccess($response);
    }
}
