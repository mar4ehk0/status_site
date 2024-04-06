<?php

namespace App\Domain\Model;

use DateInterval;
use DateTime;

class Site
{
    public function __construct(
        private string $name,
        private string $url,
        private SiteStatusEnum $status,
        private DateTime $time,
        private int $successCode,
    ) {
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function isUp(): bool
    {
        return $this->status === SiteStatusEnum::Up;
    }

    public function isDown(): bool
    {
        return $this->status === SiteStatusEnum::Down;
    }

    public function setUpAndCalculateDowntime(): DateInterval
    {
        $this->status = SiteStatusEnum::Up;
        $downtime = $this->time->diff(new DateTime());

        $this->time = new DateTime();
        return $downtime;
    }

    public function setDown(): void
    {
        $this->status = SiteStatusEnum::Down;
        $this->time = new DateTime();
    }

    public function getSuccessCode(): int
    {
        return $this->successCode;
    }
}
