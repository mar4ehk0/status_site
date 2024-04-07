<?php

namespace App\UseCase;

use App\Domain\Repository\SiteRepositoryInterface;
use App\Domain\Service\NotifierInterface;
use App\Infrastructure\Client;
use App\Infrastructure\Service\Message;
use DateTime;

class SiteCheckerUseCase
{
    public function __construct(
        private Client $client,
        private SiteRepositoryInterface $siteRepository,
        private NotifierInterface $notifier,
        private Message $message
    ) {

    }

    public function handle(): bool
    {
        $sites = $this->siteRepository->getAll();
        foreach ($sites as $site) {
            $response = $this->client->get($site->getUrl());
            if ($response->getStatusCode() !== $site->getSuccessCode() && $site->isUp()) {
                // send message = 'site is down' and change status site. save status sites.
                $site->setDown();
                $msgDown = $this->message->createMsgDown($site, new DateTime());
                $this->notifier->send($msgDown);
            } elseif ($response->getStatusCode() === $site->getSuccessCode() && $site->isDown()) {
                // send message = 'site is up' and change status site. save status sites.
                $timeInterval = $site->setUpAndCalculateDowntime();
                $msgUp = $this->message->createMsgUp($site, new DateTime(), $timeInterval);
                $this->notifier->send($msgUp);
            }
            $this->siteRepository->update($site);
        }

        return true;
    }
}
