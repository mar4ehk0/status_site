<?php

namespace App\Infrastructure\Service\Notifier;

use App\Domain\Model\Site;
use App\Domain\Service\NotifierInterface;
use App\Infrastructure\Client;
use App\Infrastructure\Service\MessageGenerator;
use DateInterval;
use DateTime;

class SlackNotifier implements NotifierInterface
{
    public function __construct(
        private Client $client,
        private string $urlSlack,
        private MessageGenerator $messageGenerator
    ) {
    }

    public function sendMessageDown(Site $site, DateTime $time): void
    {
        $message = $this->messageGenerator->createMsgDown($site, $time);

        $preparedMessage = $this->prepareMessage($message);

        $this->client->post($this->urlSlack, $preparedMessage);
    }

    public function sendMessageUp(Site $site, DateTime $time, DateInterval $interval): void
    {
        $message = $this->messageGenerator->createMsgUp($site, $time, $interval);

        $preparedMessage = $this->prepareMessage($message);

        $this->client->post($this->urlSlack, $preparedMessage);
    }

    private function prepareMessage(string $message): string
    {
        return json_encode(['text' => $message], JSON_THROW_ON_ERROR);
    }
}
