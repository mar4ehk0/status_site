<?php

namespace App\Domain\Service;

use App\Domain\Model\Site;
use DateInterval;
use DateTime;

interface NotifierInterface
{
    public function sendMessageDown(Site $site, DateTime $time): void;
    public function sendMessageUp(Site $site, DateTime $time, DateInterval $interval): void;
}
