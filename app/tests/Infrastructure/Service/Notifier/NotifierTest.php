<?php

namespace Infrastructure\Service\Notifier;

use App\Domain\Model\Site;
use App\Domain\Model\SiteStatusEnum;
use App\Domain\Service\NotifierInterface;
use App\Infrastructure\Service\Notifier\Notifier;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class NotifierTest extends TestCase
{
    private NotifierInterface $notifier1;
    private NotifierInterface $notifier2;
    private NotifierInterface $notifier3;

    protected function setUp(): void
    {
        parent::setUp();

        $this->notifier1 = $this->createMock(NotifierInterface::class);
        $this->notifier2 = $this->createMock(NotifierInterface::class);
        $this->notifier3 = $this->createMock(NotifierInterface::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->notifier1, $this->notifier2, $this->notifier3);
    }

    public function testCantSendMessageDown(): void
    {
        $this->notifier1->expects($this->once())->method('sendMessageDown');
        $this->notifier2->expects($this->once())->method('sendMessageDown');
        $this->notifier3->expects($this->once())->method('sendMessageDown');

        $time = DateTime::createFromFormat("Y-m-d H:i:s", "2024-04-06 12:10:00");
        $status = SiteStatusEnum::Up;
        $site = new Site('siteName', 'http://example.com', $status, $time, 200);

        $notifier = new Notifier($this->notifier1, $this->notifier2, $this->notifier3);
        $notifier->sendMessageDown($site, new DateTime("2024-04-06 12:15:00"));
    }

    public function testCantSendMessageUp(): void
    {
        $this->notifier1->expects($this->once())->method('sendMessageUp');
        $this->notifier2->expects($this->once())->method('sendMessageUp');
        $this->notifier3->expects($this->once())->method('sendMessageUp');

        $time = DateTime::createFromFormat("Y-m-d H:i:s", "2024-04-06 12:10:00");
        $status = SiteStatusEnum::Up;
        $site = new Site('siteName', 'http://example.com', $status, $time, 200);
        $interval = $site->setUpAndCalculateDowntime(new DateTime());

        $notifier = new Notifier($this->notifier1, $this->notifier2, $this->notifier3);
        $notifier->sendMessageUp($site, new DateTime("2024-04-06 12:15:00"), $interval);
    }
}
