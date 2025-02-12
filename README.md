# Php client for Keenetic Routers Api.

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

$response = $keenetic->api->devices()->listDevices();

foreach ($response->json('host') as $device) {
    echo $device['name'];
}
```

## List of available API methods

API client is currently under development. 
Presently, the following methods are available:

- Devices
  - List devices
  - Register device
  - Unregister device
  - Set device IP
  - Unset device IP
- System
  - Get default config
  - Get running config
  - Get startup config 
  - Get log

More methods will be added in the future. 

## License

This package is released under the [MIT License](LICENSE.md).
