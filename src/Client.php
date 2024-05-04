<?php

declare(strict_types=1);

namespace PhpClient\KeeneticRouter;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Cookie\FileCookieJar;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Storage;
use PhpClient\KeeneticRouter\Payloads\Device;
use PhpClient\Support\ValueObjects\Hostname;
use PhpClient\Support\ValueObjects\IpAddressV4;
use PhpClient\Support\ValueObjects\MacAddress;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use stdClass;

final class Client
{
    private HttpClient $http;

    /**
     * @throws GuzzleException
     */
    public function __construct(
        private readonly string $uri,
        private readonly string $login,
        private readonly string $password,
    ) {
        $this->http = new HttpClient(
            config: [
                'base_uri' => $this->uri,
                RequestOptions::COOKIES => $this->fileCookieJar(),
                RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                ],
            ]
        );

        $this->ensureAuthenticated() ?: throw new RuntimeException(message: 'Keenetic auth failed');
    }

    private function fileCookieJar(): FileCookieJar
    {
        $directory = 'keenetic/cookies/';
        $filename = sha1(string: $this->uri . $this->login);

        $storage = Storage::drive(name: 'local');
        $storage->makeDirectory(path: $directory);

        return new FileCookieJar(
            cookieFile: $storage->path(path: $directory . $filename),
            storeSessionCookies: true,
        );
    }

    /**
     * @throws GuzzleException
     */
    private function ensureAuthenticated(): bool
    {
        // Check authentication status
        $checkResponse = $this->http->get(
            uri: 'auth',
            options: [
                RequestOptions::HTTP_ERRORS => false,
            ],
        );

        // If client is already authenticated, then return success result.
        if ($checkResponse->getStatusCode() === 200) {
            return true;
        }

        // Otherwise, we prepare authentication process
        $realm = $checkResponse->getHeaderLine('X-NDM-Realm');
        $challenge = $checkResponse->getHeaderLine('X-NDM-Challenge');
        $hashPassword = hash(
            algo: 'sha256',
            data: $challenge . md5(string: "$this->login:$realm:$this->password"),
        );

        // Then run it
        $authResponse = $this->authenticate(hashPassword: $hashPassword);

        // And return status
        return $authResponse->getStatusCode() === 200;
    }

    /**
     * @throws GuzzleException
     */
    private function authenticate(string $hashPassword): ResponseInterface
    {
        return $this->http->post(
            uri: "auth",
            options: [
                RequestOptions::JSON => [
                    'login' => $this->login,
                    'password' => $hashPassword,
                ],
            ],
        );
    }

    /**
     * @return list<Device>
     * @throws GuzzleException
     */
    public function listDevices(): array
    {
        $response = $this->http->get(uri: 'rci/show/ip/hotspot');
        $clients = json_decode(json: $response->getBody()->getContents())->host;

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
            array: $clients,
        );
    }

    /**
     * @throws GuzzleException
     */
    public function registerDevice(MacAddress $macAddress, string $name): bool
    {
        $response = $this->http->post(
            uri: 'rci/known/host',
            options: [
                RequestOptions::JSON => [
                    'mac' => $macAddress->value,
                    'name' => $name,
                ],
            ],
        );

        return $response->getStatusCode() === 200;
    }

    /**
     * @throws GuzzleException
     */
    public function unregisterDevice(MacAddress $macAddress): bool
    {
        $response = $this->http->post(
            uri: 'rci/known/host',
            options: [
                RequestOptions::JSON => [
                    'mac' => $macAddress->value,
                    'no' => true,
                ],
            ],
        );

        return $response->getStatusCode() === 200;
    }

    /**
     * @throws GuzzleException
     */
    public function setIpForDevice(MacAddress $macAddress, IpAddressV4 $ip): bool
    {
        $response = $this->http->post(
            uri: 'rci/ip/dhcp/host',
            options: [
                RequestOptions::JSON => [
                    'mac' => $macAddress->value,
                    'ip' => $ip->value,
                ],
            ],
        );

        return $response->getStatusCode() === 200;
    }

    /**
     * @throws GuzzleException
     */
    public function unsetIpForDevice(MacAddress $macAddress): bool
    {
        $response = $this->http->post(
            uri: 'rci/ip/dhcp/host',
            options: [
                RequestOptions::JSON => [
                    'mac' => $macAddress->value,
                    'no' => true,
                ],
            ],
        );

        return $response->getStatusCode() === 200;
    }
}
