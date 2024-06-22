<?php

declare(strict_types=1);

namespace PhpClient\Keenetic\Requests;

use PhpClient\Keenetic\Dto\Credentials;
use PhpClient\Keenetic\Dto\RealmContext;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

final class PostAuthRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        private readonly Credentials $credentials,
        private readonly RealmContext $realmContext,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/auth';
    }

    protected function defaultBody(): array
    {
        return [
            'login' => $this->credentials->login,
            'password' => $this->passwordHash(),
        ];
    }

    private function passwordHash(): string
    {
        return hash(
            algo: 'sha256',
            data: $this->realmContext->challenge . md5(
                string: "{$this->credentials->login}:{$this->realmContext->realm}:{$this->credentials->password}"
            ),
        );
    }
}
