<?php
/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

use Dotenv\Dotenv;
use PhpClient\Keenetic\Dto\Credentials;
use PhpClient\Keenetic\Keenetic;
use PhpClient\Keenetic\Requests\Devices\ListDevicesRequest;

Dotenv::createImmutable(paths: __DIR__."/../..")->load();
$baseUrl = $_ENV['KEENETIC_BASE_URL'] ?? '';
$login = $_ENV['KEENETIC_LOGIN'] ?? '';
$password = $_ENV['KEENETIC_PASSWORD'] ?? '';

$keenetic = new Keenetic(
    baseUrl: $baseUrl,
    login: $login,
    password: $password,
);

test(
    description: 'show ip hotspot',
    closure: function () use ($keenetic) {
        $getShowIpHotspotRequest = new ListDevicesRequest();
        $response = $keenetic->send(
            request: $getShowIpHotspotRequest,
        );

        expect(value: $response->json())
            ->toBeArray()
            ->toHaveKey(key: 'host');
    },
);

test(
    description: 'common actions',
    closure: function () use ($keenetic) {
        $response = $keenetic->api->devices()->listDevices();
        dd($response->json());
    },
);