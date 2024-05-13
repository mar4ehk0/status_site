<?php

namespace Infrastructure\Service;

use App\Domain\Model\Site;
use App\Domain\Model\SiteStatusEnum;
use App\Infrastructure\Service\MessageGenerator;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class MessageGeneratorTest extends TestCase
{
    private const TMP_MSG_DOWN = '{SITE_NAME} down. It happens: {EVENT_TIME}';
    private const TMP_MSG_UP = '{SITE_NAME} up. It happens: {EVENT_TIME}. Site was downtime: {DOWNTIME_TIME}';

    public function testFailCreateMsgDown(): void
    {
        // Arrange
        $wrongTemplateDown = 'wrong template down';
        $message = new MessageGenerator($wrongTemplateDown, self::TMP_MSG_UP);
        $time = DateTime::createFromFormat("Y-m-d H:i:s", "2024-04-06 12:10:00");
        $status = SiteStatusEnum::Up;
        $site = new Site('siteName', 'http://example.com', $status, $time, 200);

        // Act
        $messageDown = $message->createMsgDown($site, new DateTime("2024-04-06 12:15:00"));

        // Assert
        $this->assertEquals($wrongTemplateDown, $messageDown);
    }

    public function testFailCreateMsgUp(): void
    {
        // Arrange
        $wrongTemplateUp = 'wrong template up';
        $message = new MessageGenerator(self::TMP_MSG_DOWN, $wrongTemplateUp);
        $time = DateTime::createFromFormat("Y-m-d H:i:s", "2024-04-06 12:10:00");
        $status = SiteStatusEnum::Up;
        $site = new Site('siteName', 'http://example.com', $status, $time, 200);
        $dateTimeUp = new DateTime("2024-04-06 12:15:00");
        $timeInterval = $site->setUpAndCalculateDowntime(clone($dateTimeUp));

        // Act
        $messageUp = $message->createMsgUp($site, new DateTime("2024-04-06 12:15:00"), $timeInterval);

        // Assert
        $this->assertEquals($wrongTemplateUp, $messageUp);
    }

    public function testCanCreateMsgDown(): void
    {
        // Arrange
        $message = new MessageGenerator(self::TMP_MSG_DOWN, self::TMP_MSG_UP);
        $time = DateTime::createFromFormat("Y-m-d H:i:s", "2024-04-06 12:10:00");
        $status = SiteStatusEnum::Up;
        $site = new Site('siteName', 'http://example.com', $status, $time, 200);

        // Act
        $messageDown = $message->createMsgDown($site, new DateTime("2024-04-06 12:15:00"));

        // Assert
        $this->assertEquals('siteName down. It happens: 2024-04-06T12:15:00+00:00', $messageDown);
    }

    public function testCanCreateMsgUp(): void
    {
        // Arrange
        $message = new MessageGenerator(self::TMP_MSG_DOWN, self::TMP_MSG_UP);
        $time = DateTime::createFromFormat("Y-m-d H:i:s", "2024-04-06 12:10:00");
        $status = SiteStatusEnum::Down;
        $site = new Site('siteName', 'http://example.com', $status, $time, 200);
        $dateTimeUp = new DateTime("2024-04-06 12:15:00");
        $timeInterval = $site->setUpAndCalculateDowntime(clone($dateTimeUp));

        // Act
        $messageDown = $message->createMsgUp($site, clone($dateTimeUp), $timeInterval);

        // Assert
        $this->assertEquals('siteName up. It happens: 2024-04-06T12:15:00+00:00. Site was downtime: 5 минута', $messageDown);
    }
}
