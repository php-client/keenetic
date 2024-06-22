<?php

declare(strict_types=1);

namespace PhpClient\Keenetic\Dto;

final readonly class RealmContext
{
    public function __construct(
        public string $realm,
        public string $challenge,
    ) {
    }
}
