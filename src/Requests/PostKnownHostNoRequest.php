<?php

declare(strict_types=1);

namespace PhpClient\Keenetic\Requests;

use PhpClient\Keenetic\Dto\Result;
use PhpClient\Support\ValueObjects\MacAddress;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

final class PostKnownHostNoRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        private readonly MacAddress $mac,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/rci/known/host';
    }

    protected function defaultBody(): array
    {
        return [
            'mac' => $this->mac->value,
            'no' => true,
        ];
    }

    public function createDtoFromResponse(Response $response): Result
    {
        return new Result(isSuccessful: $response->ok());
    }
}
