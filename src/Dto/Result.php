<?php

declare(strict_types=1);

namespace PhpClient\Keenetic\Dto;

final readonly class Result
{
    public function __construct(
        public bool $isSuccessful,
    ) {
    }
}
