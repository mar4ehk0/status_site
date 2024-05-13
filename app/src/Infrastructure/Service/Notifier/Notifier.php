<?php

namespace App\Infrastructure\Service\Notifier;

use App\Domain\Model\Site;
use App\Domain\Service\NotifierInterface;
use DateInterval;
use DateTime;

class Notifier implements NotifierInterface
{
    /**
     * @var NotifierInterface[]
     */
    private array $notifiers;

    public function __construct(NotifierInterface ...$notifiers)
    {
        $this->notifiers = $notifiers;
    }

    public function sendMessageDown(Site $site, DateTime $time): void
    {
        foreach ($this->notifiers as $notifier) {
            $notifier->sendMessageDown($site, $time);
        }
    }

    public function sendMessageUp(Site $site, DateTime $time, DateInterval $interval): void
    {
        foreach ($this->notifiers as $notifier) {
            $notifier->sendMessageUp($site, $time, $interval);
        }
    }
}
