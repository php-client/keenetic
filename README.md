# Php client for Keenetic Api.

This is a PHP client for the [Keenetic Routers API](https://help.keenetic.com/hc/en-us).

## Installation
Install the package via composer:

```bash
composer require php-client/keenetic
```

## Usage

Simple example:
```php
use PhpClient\Keenetic\Keenetic;

$keenetic = new Keenetic(
    // Replace with real values for your router:
    baseUrl: 'http://192.168.1.1',
    login: 'admin',
    password: 'admin',
);

$keenetic->auth();

$devices = $keenetic->api->devices()->listDevices();

foreach ($response->json('host') as $device) {
    echo $device['name'];
}
```

TODO: Add more usage instructions for other endpoints...

## License

This package is released under the [MIT License](LICENSE.md).
