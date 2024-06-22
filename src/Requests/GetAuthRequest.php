<?php

declare(strict_types=1);

namespace PhpClient\Keenetic\Requests;

use PhpClient\Keenetic\Dto\RealmContext;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

final class GetAuthRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/auth';
    }

    public function createDtoFromResponse(Response $response): RealmContext
    {
        return new RealmContext(
            realm: $response->header(header: 'X-NDM-Realm'),
            challenge: $response->header(header: 'X-NDM-Challenge'),
        );
    }
}
