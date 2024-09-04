<?php

declare(strict_types=1);

namespace PhpClient\Keenetic\Resources;

use PhpClient\Keenetic\Requests\Devices\ListDevicesRequest;
use PhpClient\Keenetic\Requests\Devices\RegisterDeviceRequest;
use PhpClient\Keenetic\Requests\Devices\SetDeviceIpRequest;
use PhpClient\Keenetic\Requests\Devices\UnregisterDeviceRequest;
use PhpClient\Keenetic\Requests\Devices\UnsetDeviceIpRequest;
use Saloon\Exceptions\SaloonException;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

final class DevicesResource extends BaseResource
{
    /**
     * @throws SaloonException
     */
    public function listDevices(): Response
    {
        return $this->connector->send(
            request: new ListDevicesRequest(),
        );
    }

    /**
     * @throws SaloonException
     */
    public function registerDevice(string $macAddress, string $name): Response
    {
        return $this->connector->send(
            request: new RegisterDeviceRequest(
                macAddress: $macAddress,
                name: $name,
            ),
        );
    }

    /**
     * @throws SaloonException
     */
    public function unregisterDevice(string $macAddress): Response
    {
        return $this->connector->send(
            request: new UnregisterDeviceRequest(
                macAddress: $macAddress,
            ),
        );
    }

    /**
     * @throws SaloonException
     */
    public function setDeviceIp(string $macAddress, string $ipAddress): Response
    {
        return $this->connector->send(
            request: new SetDeviceIpRequest(
                macAddress: $macAddress,
                ipAddress: $ipAddress,
            ),
        );
    }

    /**
     * @throws SaloonException
     */
    public function unsetDeviceIp(string $macAddress): Response
    {
        return $this->connector->send(
            request: new UnsetDeviceIpRequest(
                macAddress: $macAddress,
            ),
        );
    }
}
