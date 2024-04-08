<?php

namespace App\Infrastructure\Service;

use App\Domain\Model\Site;
use App\Infrastructure\DateTimeInterval;
use DateInterval;
use DateTime;

class Message
{
    public function __construct(
        private string $templateMsgDown,
        private string $templateMsgUp
    ) {
    }

    public function createMsgDown(Site $site, DateTime $time): string
    {
        $result = sprintf($this->templateMsgDown, $site->getName(), $time->format(DATE_ATOM));

        return $result;
    }

    public function createMsgUp(Site $site, DateTime $time, DateInterval $interval): string
    {
        $humanDowntime = DateTimeInterval::createHumanInterval($interval);
        $result = sprintf($this->templateMsgUp, $site->getName(), $time->format(DATE_ATOM), $humanDowntime);

        return $result;
    }
}
