<?php

namespace Infrastructure\Service\Notifier;

use App\Domain\Model\Site;
use App\Domain\Model\SiteStatusEnum;
use App\Infrastructure\Client;
use App\Infrastructure\Service\MessageGenerator;
use App\Infrastructure\Service\Notifier\SlackNotifier;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class SlackNotifierTest extends TestCase
{
    private Client $client;
    private MessageGenerator $messageGenerator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = $this->createMock(Client::class);
        $this->messageGenerator = $this->createMock(MessageGenerator::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->client, $this->messageGenerator);
    }

    public function testCanSendMessageDown(): void
    {
        $messageDown = 'message_down';
        $url = 'http://some-url-for-slack.com/v1/api';

        $this->messageGenerator->method('createMsgDown')->willReturn($messageDown);

        $preparedMessage = sprintf('{"text":"%s"}', $messageDown);

        $slackNotifier = new SlackNotifier($this->client, $url, $this->messageGenerator);
        $this->client
            ->expects($this->once())
            ->method('post')
            ->with($this->equalTo($url), $this->equalTo($preparedMessage));

        $time = DateTime::createFromFormat("Y-m-d H:i:s", "2024-04-06 12:10:00");
        $status = SiteStatusEnum::Up;
        $site = new Site('siteName', 'http://example.com', $status, $time, 200);
        $slackNotifier->sendMessageDown($site, new DateTime("2024-04-06 12:15:00"));
    }

    public function testCanSendMessageUp(): void
    {
        $messageUp = 'message_up';
        $url = 'http://some-url-for-slack.com/v1/api';

        $this->messageGenerator->method('createMsgUp')->willReturn($messageUp);

        $preparedMessage = sprintf('{"text":"%s"}', $messageUp);

        $slackNotifier = new SlackNotifier($this->client, $url, $this->messageGenerator);
        $this->client
            ->expects($this->once())
            ->method('post')
            ->with($this->equalTo($url), $this->equalTo($preparedMessage));

        $time = DateTime::createFromFormat("Y-m-d H:i:s", "2024-04-06 12:10:00");
        $status = SiteStatusEnum::Up;
        $site = new Site('siteName', 'http://example.com', $status, $time, 200);
        $interval = $site->setUpAndCalculateDowntime(new DateTime());
        $slackNotifier->sendMessageUp($site, new DateTime("2024-04-06 12:15:00"), $interval);
    }
}
