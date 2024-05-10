<?php

declare(strict_types=1);

namespace PhpClient\KeeneticRouter;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Cookie\FileCookieJar;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Storage;
use PhpClient\KeeneticRouter\Requests\AuthCheckRequest;
use PhpClient\KeeneticRouter\Requests\AuthRequest;
use PhpClient\KeeneticRouter\Responses\CommonResponse;
use RuntimeException;

final readonly class KeeneticClient
{
    private HttpClient $http;

    public CommonRequestsFactory $common;

    /**
     * @throws GuzzleException
     */
    public function __construct(
        private string $uri,
        private string $login,
        private string $password,
    ) {
        $this->http = new HttpClient(
            config: [
                'base_uri' => $this->uri,
                RequestOptions::COOKIES => $this->fileCookieJar(),
            ],
        );

        $this->ensureAuthenticated() ?: throw new RuntimeException(message: 'Keenetic auth failed');

        $this->common = new CommonRequestsFactory(client: $this);
    }

    private function fileCookieJar(): FileCookieJar
    {
        $directory = 'keenetic/cookies/';
        $filename = sha1(string: $this->uri . $this->login);

        // todo: get rid of "Illuminate\Support" dependency
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
    public function send(Request $request, array $options = []): Response
    {
        return $this->http->send(
            request: $request,
            options: $options,
        );
    }

    /**
     * @throws GuzzleException
     */
    private function ensureAuthenticated(): bool
    {
        // Check authentication status
        $request = new AuthCheckRequest();
        $response = $this->send(
            request: $request,
            options: [RequestOptions::HTTP_ERRORS => false],
        );

        // If not logged in, then authenticate
        return (new CommonResponse(response: $response))->isSuccess() || $this->authenticate(
            realm: $response->getHeaderLine(header: 'X-NDM-Realm'),
            challenge: $response->getHeaderLine(header: 'X-NDM-Challenge')
        );
    }

    /**
     * @throws GuzzleException
     */
    public function authenticate(string $realm, string $challenge): bool
    {
        $request = new AuthRequest(
            login: $this->login,
            password: $this->password,
            realm: $realm,
            challenge: $challenge,
        );

        $response = $this->send(request: $request);
        $customResponse = new CommonResponse(response: $response);

        // And return status
        return $customResponse->isSuccess();
    }
}
