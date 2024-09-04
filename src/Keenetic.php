<?php

declare(strict_types=1);

namespace PhpClient\Keenetic;

use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\CookieJarInterface;
use PhpClient\Keenetic\Resources\Api;
use PhpClient\Keenetic\Resources\AuthResource;
use Saloon\Exceptions\SaloonException;
use Saloon\Http\Connector;
use Saloon\Traits\Plugins\HasTimeout;

final class Keenetic extends Connector
{
    use HasTimeout;

    public readonly Api $api;

    /**
     * @throws SaloonException
     */
    public function __construct(
        private readonly string $baseUrl,
        private readonly string $login,
        private readonly string $password,
        private readonly ?CookieJarInterface $cookieJar = null,
    ) {
        $this->api = new Api(connector: $this);
        $this->auth();
    }

    protected function defaultConfig(): array
    {
        return [
            'cookies' => $this->cookieJar ?: new CookieJar(),
        ];
    }

    public function resolveBaseUrl(): string
    {
        return $this->baseUrl;
    }

    protected function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * @throws SaloonException
     */
    private function auth(): void
    {
        $auth = new AuthResource(connector: $this);

        $auth->login(
            login: $this->login,
            password: $this->password,
        );
    }
}
