<?php

namespace App\Infrastructure\Service;

use App\Domain\Model\Site;
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
        $humanDowntime = $this->createHumanDownTime($interval);
        $result = sprintf($this->templateMsgUp, $site->getName(), $time->format(DATE_ATOM), $humanDowntime);

        return $result;
    }

    private function createHumanDownTime(DateInterval $dateInterval)
    {
        $timeInterval = [];
        if ($dateInterval->y) {
            $timeInterval[] = $dateInterval->y . ' year';
        }
        if ($dateInterval->m) {
            $timeInterval[] = $dateInterval->m . ' month';
        }
        if ($dateInterval->d) {
            $timeInterval[] = $dateInterval->d . ' day';
        }
        if ($dateInterval->h) {
            $timeInterval[] = $dateInterval->h . ' hour';
        }
        if ($dateInterval->i) {
            $timeInterval[] = $dateInterval->i . ' minute';
        }
        if ($dateInterval->s) {
            $timeInterval[] = $dateInterval->s . ' second';
        }

        return implode(' ', $timeInterval);
    }
}
