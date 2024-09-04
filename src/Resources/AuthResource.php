<?php

declare(strict_types=1);

namespace PhpClient\Keenetic\Resources;

use PhpClient\Keenetic\Requests\Auth\AuthStatusRequest;
use PhpClient\Keenetic\Requests\Auth\LoginRequest;
use Saloon\Exceptions\SaloonException;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

final class AuthResource extends BaseResource
{
    /**
     * @throws SaloonException
     */
    public function login(string $login, string $password): Response
    {
        $authStatusResponse = $this->authStatus();

        if ($authStatusResponse->ok()) {
            return $authStatusResponse;
        }

        $loginRequest = new LoginRequest(
            login: $login,
            password: $password,
            realm: $authStatusResponse->header(header: 'X-NDM-Realm'),
            challenge: $authStatusResponse->header(header: 'X-NDM-Challenge'),
        );
        $loginResponse = $this->connector->send(request: $loginRequest);

        if ($loginResponse->ok()) {
            return $loginResponse;
        }

        throw new SaloonException(
            message: 'Failed to authenticate.',
            request: $loginRequest,
            response: $loginResponse,
        );
    }

    /**
     * @throws SaloonException
     */
    private function authStatus(): Response
    {
        return $this->connector->send(
            request: new AuthStatusRequest(),
        );
    }
}
