<?php

namespace App\Infrastructure\Components\GuzzleHttp;

use App\Domain\Components\Http\ResponseInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class Response implements ResponseInterface
{
    public function __construct(
        private PsrResponseInterface $response
    ) {}

    public function getStatusCode(): int
    {
        return $this->response->getStatusCode();
    }

    public function getString(): string
    {
        return $this->response->getBody()->getContents();
    }

    public function getFromJson(): array
    {
        return json_decode($this->getString(), true);
    }

    public function getFromQuery(): array
    {
        $array = [];
        parse_str($this->getString(), $array);
        return $array;
    }
}
