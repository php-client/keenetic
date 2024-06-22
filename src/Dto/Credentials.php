<?php

declare(strict_types=1);

namespace PhpClient\Keenetic\Dto;

final readonly class Credentials
{
    public function __construct(
        public string $login,
        public string $password,
    ) {
    }
}
