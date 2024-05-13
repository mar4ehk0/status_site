<?php

namespace App\UseCase;

use App\Domain\Model\Site;
use App\Domain\Repository\SiteRepositoryInterface;
use App\Domain\Service\NotifierInterface;
use App\Infrastructure\Client;
use App\Infrastructure\Exception\TransportException;
use DateTime;

class SiteCheckerUseCase
{
    public function __construct(
        private Client $client,
        private SiteRepositoryInterface $siteRepository,
        private NotifierInterface $notifier
    ) {
    }

    public function handle(): void
    {
        $sites = $this->siteRepository->getAll();
        foreach ($sites as $site) {
            try {
                $response = $this->client->get($site->getUrl());
                if ($response->getStatusCode() !== $site->getSuccessCode() && $site->isUp()) {
                    $site = $this->setDownAndSendMessageDown($site);
                } elseif ($response->getStatusCode() === $site->getSuccessCode() && $site->isDown()) {
                    $site = $this->setUpAndSendMessageUp($site);
                }
            } catch (TransportException) {
                if ($site->isUp()) {
                    $site = $this->setDownAndSendMessageDown($site);
                }
            }

            $this->siteRepository->update($site);
        }
    }

    private function setDownAndSendMessageDown(Site $site): Site
    {
        // send message = 'site is down' and change status site. save status sites.
        $site->setDown(new DateTime());
        $this->notifier->sendMessageDown($site, new DateTime());

        return $site;
    }

    private function setUpAndSendMessageUp(Site $site): Site
    {
        // send message = 'site is up' and change status site. save status sites.
        $timeInterval = $site->setUpAndCalculateDowntime(new DateTime());
        $this->notifier->sendMessageUp($site, new DateTime(), $timeInterval);

        return $site;
    }
}
