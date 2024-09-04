<?php

declare(strict_types=1);

namespace PhpClient\Keenetic\Requests\Auth;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

final class LoginRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        private readonly string $login,
        private readonly string $password,
        private readonly string $realm,
        private readonly string $challenge,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/auth';
    }

    protected function defaultBody(): array
    {
        return [
            'login' => $this->login,
            'password' => $this->passwordHash(),
        ];
    }

    private function passwordHash(): string
    {
        return hash(
            algo: 'sha256',
            data: $this->challenge.md5(
                string: "$this->login:$this->realm:$this->password",
            ),
        );
    }
}
