<?php

namespace App\Infrastructure\Components\GuzzleHttp;

use App\Domain\Components\Http\ClientInterface;
use App\Domain\Components\Http\ResponseInterface;
use GuzzleHttp\Client as GuzzleClient;

class Client implements ClientInterface
{
    private string $url;
    private array $options = [];

    public function __construct(
        private GuzzleClient $client
    ) {
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;
        return $this;
    }

    public function setBody(array $body): static
    {
        $this->options['body'] = $body;
        return $this;
    }

    public function setQuery(array $query): static
    {
        $this->options['query'] = $query;
        return $this;
    }

    public function setFormParams(array $formParams): static
    {
        $this->options['form_params'] = $formParams;
        return $this;
    }

    public function setHeaders(array $headers): static
    {
        $this->options['headers'] = $headers;
        return $this;
    }

    private function request(string $method): ResponseInterface
    {
        $response = $this->client->$method($this->url, $this->options);
        return new Response(
            response: $response
        );
    }


    public function requestGet(): ResponseInterface
    {
        return $this->request("get");
    }

    public function requestPost(): ResponseInterface
    {
        return $this->request("post");
    }

    public function requestPut(): ResponseInterface
    {
        return $this->request("put");
    }

    public function requestPatch(): ResponseInterface
    {
        return $this->request("patch");
    }

    public function requestDelete(): ResponseInterface
    {
        return $this->request("delete");
    }
}
