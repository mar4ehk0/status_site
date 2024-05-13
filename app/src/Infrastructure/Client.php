<?php

namespace App\Infrastructure;

use App\Infrastructure\Exception\TransportException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class Client
{
    private const GET = 'GET';
    private const POST = 'POST';
    private string $maxDuration;

    public function __construct(
        private HttpClientInterface $client,
        string $maxDuration
    ) {
        $this->maxDuration = (int) $maxDuration;
    }

    /**
     * @throws TransportException
     */
    public function get(string $url): ResponseInterface
    {
        try {
            $response = $this->client->request(self::GET, $url, ['max_duration' => $this->maxDuration]);
            // необходимо получить заголовки, чтобы убрать реализацию lazy-load'a для request'a.
            $response->getHeaders();
        } catch (TransportExceptionInterface $e) {
            throw new TransportException($url);
        } catch (ServerExceptionInterface | ClientExceptionInterface | RedirectionExceptionInterface $e) {
            $response = $e->getResponse();
        }

        return $response;
    }

    public function post(string $url, string $data): ResponseInterface
    {
        $options['body'] = $data;
        if (json_validate($data)) {
            $options['headers'][] = 'Content-type: application/json';
        }

        return $this->client->request(self::POST, $url, $options);
    }
}
