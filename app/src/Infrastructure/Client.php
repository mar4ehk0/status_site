<?php

namespace App\Infrastructure;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class Client
{
    private const GET = 'GET';

    public function __construct(
        private HttpClientInterface $client
    ) {
    }

    public function get(string $url): ResponseInterface
    {
        return $this->client->request(self::GET, $url);
    }
}
