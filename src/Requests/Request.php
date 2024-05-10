<?php

declare(strict_types=1);

namespace PhpClient\KeeneticRouter\Requests;

use GuzzleHttp\Psr7\Request as GuzzleRequest;
use PhpClient\Support\Enums\HttpMethod;

abstract class Request extends GuzzleRequest
{
    public function __construct(HttpMethod $method, string $uri, array $data = [])
    {
        parent::__construct(
            method: $method->value,
            uri: $uri,
            headers: [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            body: json_encode(
                value: $data,
            ),
        );
    }
}
