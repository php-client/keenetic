<?php

declare(strict_types=1);

namespace PhpClient\KeeneticRouter\Responses;

use GuzzleHttp\Psr7\Response as GuzzleResponse;
use PhpClient\Support\Enums\HttpStatus;

abstract class Response
{
    protected HttpStatus $successStatusCode = HttpStatus::OK;

    public function __construct(
        readonly protected GuzzleResponse $response
    ) {
    }

    public function isSuccess(): bool
    {
        return $this->response->getStatusCode() === $this->successStatusCode->value;
    }

    public function decodedContent(): mixed
    {
        return json_decode(
            json: $this->content()
        );
    }

    public function content(): string
    {
        return $this->response->getBody()->getContents();
    }
}
