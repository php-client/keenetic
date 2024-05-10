<?php

declare(strict_types=1);

namespace PhpClient\KeeneticRouter\Requests;

use PhpClient\Support\Enums\HttpMethod;

final class AuthRequest extends Request
{
    public function __construct(string $login, string $password, string $realm, string $challenge)
    {
        $preHash = md5(string: "$login:$realm:$password");
        $passwordHash = hash(algo: 'sha256', data: $challenge . $preHash);

        parent::__construct(
            method: HttpMethod::POST,
            uri: 'auth',
            data: ['login' => $login, 'password' => $passwordHash],
        );
    }
}
