<?php

namespace App\UseCase;

use App\Domain\Repository\SiteRepositoryInterface;
use App\Infrastructure\Client;

class SiteCheckerUseCase
{
    public function __construct(
        private Client $client,
        private SiteRepositoryInterface $siteRepository
    ) {

    }

    public function handle(): bool
    {
        $sites = $this->siteRepository->getAll();
        foreach ($sites as $site) {
            $response = $this->client->get($site->getUrl());
            var_dump($response->getStatusCode());

            if ($response->getStatusCode() === $site->getSuccessCode() && $site->isUp()) {
                // not send
            } elseif ($response->getStatusCode() !== $site->getSuccessCode() && $site->isUp()) {
                // send message = 'site is down' and change status site. save status sites.
            } elseif ($response->getStatusCode() !== $site->getSuccessCode() && $site->isDown()) {
                // not send
            } elseif ($response->getStatusCode() === $site->getSuccessCode() && $site->isDown()) {
                // send message = 'site is up' and change status site. save status sites.
            }
        }

        return true;
    }
}
