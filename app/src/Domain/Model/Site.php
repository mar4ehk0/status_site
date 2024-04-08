<?php

namespace App\Domain\Model;

use DateInterval;
use DateTime;
use Exception;

class Site
{
    public function __construct(
        private string $name,
        private string $url,
        private SiteStatusEnum $status,
        private DateTime $time,
        private int $successCode,
    ) {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new Exception('Url has wrong format.');
        }
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
        $downtime = DateInterval::createFromDateString('0 seconds');
        if ($this->isDown()) {
            $downtime = $this->time->diff(new DateTime());
            $this->time = new DateTime();
        }

        $this->status = SiteStatusEnum::Up;
        return $downtime;
    }

    public function setDown(): void
    {
        if ($this->isUp()) {
            $this->time = new DateTime();
        }

        $this->status = SiteStatusEnum::Down;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStatus(): SiteStatusEnum
    {
        return $this->status;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getTime(): DateTime
    {
        return $this->time;
    }

    public function getSuccessCode(): int
    {
        return $this->successCode;
    }
}
