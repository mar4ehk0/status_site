<?php

namespace Domain;

use App\Domain\Model\Site;
use App\Domain\Model\SiteStatusEnum;
use App\Infrastructure\DateTimeInterval;
use DateInterval;
use DateTime;
use Exception;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class SiteTest extends TestCase
{
    public function testFailWhenUrlFailed(): void
    {
        $this->expectException(Exception::class);

        $time = DateTime::createFromFormat("Y-m-d H:i:s", "2024-04-06 12:10:00");
        $status = SiteStatusEnum::Down;
        new Site('siteName', 'lorem_ipsum', $status, $time, 200);
    }


    public function testCanSiteIsUp(): void
    {
        // arrange
        $time = DateTime::createFromFormat("Y-m-d H:i:s", "2024-04-06 12:10:00");
        $status = SiteStatusEnum::Up;

        // act
        $site = new Site('siteName', 'http://example.com', $status, $time, 200);

        // assert
        $this->assertTrue($site->isUp());
    }

    public function testCanSiteIsDown(): void
    {
        // arrange
        $time = DateTime::createFromFormat("Y-m-d H:i:s", "2024-04-06 12:10:00");
        $status = SiteStatusEnum::Down;

        // act
        $site = new Site('siteName', 'http://example.com', $status, $time, 200);

        // assert
        $this->assertTrue($site->isDown());
    }

    public function testCanSetUpAndCalculateDowntimeWhenSiteIsUp(): void
    {
        // arrange
        $time = DateTime::createFromFormat("Y-m-d H:i:s", "2024-04-06 12:10:00");
        $status = SiteStatusEnum::Up;
        $site = new Site('siteName', 'http://example.com', $status, $time, 200);
        $expectedInterval = DateInterval::createFromDateString('0 seconds');
        $expectedValue = DateTimeInterval::createHumanInterval($expectedInterval);

        // act
        $interval = $site->setUpAndCalculateDowntime();
        $value = DateTimeInterval::createHumanInterval($interval);
        $timeWhenWasUp = $site->getTime();

        // assert
        $this->assertTrue($site->isUp());
        $this->assertEquals($expectedValue, $value);
        $this->assertEquals($time, $timeWhenWasUp);
    }

    public function testCanSetUpAndCalculateDowntimeWhenSiteIsDown(): void
    {
        // arrange
        $time = DateTime::createFromFormat("Y-m-d H:i:s", "2024-04-06 12:10:00");
        $status = SiteStatusEnum::Down;
        $site = new Site('siteName', 'http://example.com', $status, $time, 200);
        $expectedInterval = (clone $time)->diff(new DateTime());
        $expectedValue = DateTimeInterval::createHumanInterval($expectedInterval);

        // act
        $interval = $site->setUpAndCalculateDowntime();
        $value = DateTimeInterval::createHumanInterval($interval);
        $timeWhenWasUp = $site->getTime();

        // assert
        $this->assertTrue($site->isUp());
        $this->assertEquals($expectedValue, $value);
        $this->assertNotEquals($time, $timeWhenWasUp);
    }

    public function testCanSetDownWhenSiteIsUp(): void
    {
        // arrange
        $time = DateTime::createFromFormat("Y-m-d H:i:s", "2024-04-06 12:10:00");
        $status = SiteStatusEnum::Up;
        $site = new Site('siteName', 'http://example.com', $status, $time, 200);

        // act
        $site->setDown();
        $timeWhenWasDown = $site->getTime();

        // assert
        $this->assertTrue($site->isDown());
        $this->assertNotEquals($time, $timeWhenWasDown);
    }

    public function testCanSetDownWhenSiteIsDown(): void
    {
        // arrange
        $time = DateTime::createFromFormat("Y-m-d H:i:s", "2024-04-06 12:10:00");
        $status = SiteStatusEnum::Down;
        $site = new Site('siteName', 'http://example.com', $status, $time, 200);

        // act
        $site->setDown();
        $timeWhenWasDown = $site->getTime();

        // assert
        $this->assertTrue($site->isDown());
        $this->assertEquals($time, $timeWhenWasDown);
    }

    public function testCanGetName(): void
    {
        // arrange
        $time = DateTime::createFromFormat("Y-m-d H:i:s", "2024-04-06 12:10:00");
        $status = SiteStatusEnum::Down;
        $nameExpected = 'siteName';
        $site = new Site($nameExpected, 'http://example.com', $status, $time, 200);

        // act
        $name = $site->getName();

        // assert
        $this->assertEquals($nameExpected, $name);
    }

    #[DataProvider('dataProviderCanGetStatus')]
    public function testCanGetStatus(SiteStatusEnum $expectedStatus, SiteStatusEnum $status): void
    {
        // arrange
        $time = DateTime::createFromFormat("Y-m-d H:i:s", "2024-04-06 12:10:00");
        $site = new Site('siteName', 'http://example.com', $status, $time, 200);

        // act
        $status = $site->getStatus();

        // assert
        $this->assertEquals($expectedStatus, $status);
    }

    public static function dataProviderCanGetStatus(): array
    {
        return [
            'UP' => ['expectedStatus' => SiteStatusEnum::Up, 'status' => SiteStatusEnum::Up],
            'DOWN' => ['expectedStatus' => SiteStatusEnum::Down, 'status' => SiteStatusEnum::Down],
        ];
    }

    public function testCanGetUrl(): void
    {
        // arrange
        $time = DateTime::createFromFormat("Y-m-d H:i:s", "2024-04-06 12:10:00");
        $status = SiteStatusEnum::Down;
        $expectedUrl = 'http://example.com';
        $site = new Site('siteName', $expectedUrl, $status, $time, 200);

        // act
        $url = $site->getUrl();

        // assert
        $this->assertEquals($expectedUrl, $url);
    }

    public function testCanGetTime(): void
    {
        // arrange
        $expectedTime = DateTime::createFromFormat("Y-m-d H:i:s", "2024-04-06 12:10:00");
        $status = SiteStatusEnum::Down;
        $site = new Site('siteName', 'http://example.com', $status, $expectedTime, 200);

        // act
        $time = $site->getTime();

        // assert
        $this->assertEquals($expectedTime, $time);
    }

    public function testCanGetSuccessCode(): void
    {
        // arrange
        $time = DateTime::createFromFormat("Y-m-d H:i:s", "2024-04-06 12:10:00");
        $status = SiteStatusEnum::Down;
        $expectedSuccessConde = 200;
        $site = new Site('siteName', 'http://example.com', $status, $time, $expectedSuccessConde);

        // act
        $successConde = $site->getSuccessCode();

        // assert
        $this->assertEquals($expectedSuccessConde, $successConde);
    }
}
