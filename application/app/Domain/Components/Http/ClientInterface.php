<?php

namespace App\Domain\Components\Http;

interface ClientInterface
{
    public function setUrl(string $url): static;
    public function setBody(array $body): static;
    public function setQuery(array $query): static;
    public function setFormParams(array $formParams): static;
    public function setHeaders(array $headers): static;

    public function requestGet(): ResponseInterface;
    public function requestPost(): ResponseInterface;
    public function requestPut(): ResponseInterface;
    public function requestPatch(): ResponseInterface;
    public function requestDelete(): ResponseInterface;
}
