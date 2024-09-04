<?php

declare(strict_types=1);

namespace PhpClient\Keenetic\Requests\Devices;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

final class     RegisterDeviceRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        private readonly string $macAddress,
        private readonly string $name,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/rci/known/host';
    }

    protected function defaultBody(): array
    {
        return [
            'mac' => $this->macAddress,
            'name' => $this->name,
        ];
    }
}
