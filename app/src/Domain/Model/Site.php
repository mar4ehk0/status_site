<?php

namespace App\Domain\Model;

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

    public function setUp(): void
    {
        $this->status = SiteStatusEnum::Up;
    }

    public function setDown(): void
    {
        $this->status = SiteStatusEnum::Down;
    }

    public function getSuccessCode(): int
    {
        return $this->successCode;
    }
}
