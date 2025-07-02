<?php

namespace App\Utils;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ApiClient
{
    private Client $client;

    private string $baseUri;

    private string $rawResponse;

    public function __construct(string $baseUri)
    {
        $this->baseUri = rtrim($baseUri, '/');
        $this->client = new Client(['base_uri' => $this->baseUri]);
    }

    /**
     * @throws GuzzleException
     */
    public function request(string $method, string $uri, array $options = []): ?string
    {
        $response = $this->client->request($method, $uri, $options);

        return $response->getBody()->getContents();

    }

    /**
     * @throws GuzzleException
     */
    public function get(array $params = []): static
    {
        $this->rawResponse = $this->request('GET', $this->baseUri, $params);

        return $this;
    }

    public function raw(): ?string
    {
        if (! $this->rawResponse) {
            return null;
        }

        return $this->rawResponse;
    }

    public function decode(): mixed
    {
        if (! $this->rawResponse) {
            return null;
        }

        return json_decode($this->rawResponse, true) ?? null;
    }
}
