<?php

declare(strict_types=1);

namespace PhpClient\Keenetic;

use GuzzleHttp\Cookie\CookieJar;
use PhpClient\Keenetic\Dto\RealmContext;
use PhpClient\Keenetic\Dto\Credentials;
use PhpClient\Keenetic\Exceptions\KeeneticException;
use PhpClient\Keenetic\Requests\GetAuthRequest;
use PhpClient\Keenetic\Requests\PostAuthRequest;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\Connector;
use Saloon\Http\Response;
use Saloon\Traits\Plugins\HasTimeout;
use Throwable;

final class Keenetic extends Connector
{
    use HasTimeout;
    
    public readonly CommonActions $actions;

    public function __construct(
        private readonly string $baseUrl,
        private readonly CookieJar $cookieJar = new CookieJar(),
    ) {
        $this->actions = new CommonActions(keenetic: $this);
    }

    protected function defaultConfig(): array
    {
        return [
            'cookies' => $this->cookieJar,
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
     * @throws FatalRequestException|RequestException|KeeneticException
     */
    public function auth(Credentials $credentials): Keenetic
    {
        $getAuthRequest = new GetAuthRequest();
        $getAuthResponse = $this->send(request: $getAuthRequest);

        try {

            if ($getAuthResponse->ok()) {
                return $this;
            }

            /** @var RealmContext $realmContext */
            $realmContext = $getAuthResponse->dto();
        } catch (Throwable $throwable) {
            throw new KeeneticException(
                message: 'Failed to check authentication.',
                code: $throwable->getCode(),
                previous: $throwable,
                request: $getAuthRequest,
                response: $getAuthResponse,
            );
        }

        $postAuthRequest = new PostAuthRequest(credentials: $credentials, realmContext: $realmContext);
        $postAuthResponse = $this->send(request: $postAuthRequest);

        if ($postAuthResponse->ok()) {
            return $this;
        }

        throw new KeeneticException(
            message: 'Failed to authenticate.',
            request: $postAuthRequest,
            response: $postAuthResponse,
        );
    }

    public function getRequestException(Response $response, ?Throwable $senderException): ?Throwable
    {
        return new KeeneticException(
            message: $senderException->getMessage(),
            code: $senderException->getCode(),
            previous: $senderException,
            request: $this->response?->getRequest(),
            response: $this->response
        );
    }
}
