<?php

declare(strict_types=1);

namespace PhpClient\Keenetic\Requests;

use PhpClient\Keenetic\Dto\Result;
use PhpClient\Support\ValueObjects\IpAddressV4;
use PhpClient\Support\ValueObjects\MacAddress;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

final class PostIpDhcpHostRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        private readonly MacAddress $mac,
        private readonly IpAddressV4 $ip,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/rci/ip/dhcp/host';
    }

    protected function defaultBody(): array
    {
        return [
            'mac' => $this->mac->value,
            'ip' => $this->ip->value,
        ];
    }

    public function createDtoFromResponse(Response $response): Result
    {
        return new Result(isSuccessful: $response->ok());
    }
}
