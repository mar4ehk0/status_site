<?php

namespace App\Infrastructure\Service;

use App\Domain\Model\Site;
use App\Infrastructure\DateTimeInterval;
use DateInterval;
use DateTime;

class MessageGenerator
{
    public function __construct(
        private string $templateMsgDown,
        private string $templateMsgUp
    ) {
    }

    public function createMsgDown(Site $site, DateTime $time): string
    {
        $result = strtr(
            $this->templateMsgDown,
            [
                '{SITE_NAME}' => $site->getName(),
                '{EVENT_TIME}' => $time->format(DATE_ATOM),
            ]
        );

        return $result;
    }

    public function createMsgUp(Site $site, DateTime $time, DateInterval $interval): string
    {
        $humanDowntime = DateTimeInterval::createHumanInterval($interval);
        $result = strtr(
            $this->templateMsgUp,
            [
                '{SITE_NAME}' => $site->getName(),
                '{EVENT_TIME}' => $time->format(DATE_ATOM),
                '{DOWNTIME_TIME}' => $humanDowntime,
            ]
        );

        return $result;
    }
}
