<?php
/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

use Dotenv\Dotenv;
use PhpClient\Keenetic\Dto\Credentials;
use PhpClient\Keenetic\Keenetic;
use PhpClient\Keenetic\Requests\GetShowIpHotspotRequest;

Dotenv::createImmutable(paths: __DIR__ . "/../..")->load();
$baseUrl = $_ENV['KEENETIC_BASE_URL'] ?? '';
$keenetic = new Keenetic(baseUrl: $baseUrl);

test(
    description: 'auth',
    closure: function () use ($keenetic) {
        $login = $_ENV['KEENETIC_LOGIN'] ?? '';
        $password = $_ENV['KEENETIC_PASSWORD'] ?? '';
        $response = $keenetic->auth(
            credentials: new Credentials(
                login: $login,
                password: $password,
            ),
        );

        expect(value: $response)
            ->toBeInstanceOf(class: Keenetic::class);
    },
);

test(
    description: 'show ip hotspot',
    closure: function () use ($keenetic) {
        $getShowIpHotspotRequest = new GetShowIpHotspotRequest();
        $response = $keenetic->send(
            request: $getShowIpHotspotRequest,
        );

        expect(value: $response->json())
            ->toBeArray()
            ->toHaveKey(key: 'host');

//        /** @var DeviceCollection $deviceCollection */
//        $deviceCollection = $response->dtoOrFail();
//        dd($deviceCollection->where('online', true)->whereStartWith('name', 'sento'));
//        dd($deviceCollection->where('online', true)->whereEndsWith('name', 'eth'));
    },
);

test(
    description: 'common actions',
    closure: function () use ($keenetic) {
        $devices = $keenetic->actions->listDevices();
        dd($devices);
    },
);