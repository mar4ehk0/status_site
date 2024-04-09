<?php

namespace Infrastructure;

use App\Infrastructure\DateTimeInterval;
use DateTime;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class DateTimeIntervalTest extends TestCase
{
    #[DataProvider('createDataProviderCanCreateHumanInterval')]
    public function testCanCreateHumanInterval(
        string $expectedHumanTime,
        string $startDateTime,
        string $endDateTime,
    ): void {
        // Arrange
        $interval = (DateTime::createFromFormat("Y-m-d H:i:s", $startDateTime))
            ->diff(DateTime::createFromFormat("Y-m-d H:i:s", $endDateTime));

        // Act
        $result = DateTimeInterval::createHumanInterval($interval);

        // Assert
        $this->assertEquals($expectedHumanTime, $result);
    }

    public static function createDataProviderCanCreateHumanInterval(): array
    {
        return [
            [
                'expectedHumanTime' => '',
                'startDateTime' => '2024-04-09 10:15:00',
                'endDateTime' => '2024-04-09 10:15:00',
            ],
            [
                'expectedHumanTime' => '1 second',
                'startDateTime' => '2024-04-09 10:15:00',
                'endDateTime' => '2024-04-09 10:15:01',
            ],
            [
                'expectedHumanTime' => '5 second',
                'startDateTime' => '2024-04-09 10:15:00',
                'endDateTime' => '2024-04-09 10:15:05',
            ],
            [
                'expectedHumanTime' => '1 minute',
                'startDateTime' => '2024-04-09 10:15:00',
                'endDateTime' => '2024-04-09 10:16:00',
            ],
            [
                'expectedHumanTime' => '5 minute',
                'startDateTime' => '2024-04-09 10:15:00',
                'endDateTime' => '2024-04-09 10:20:00',
            ],
            [
                'expectedHumanTime' => '1 hour',
                'startDateTime' => '2024-04-09 10:15:00',
                'endDateTime' => '2024-04-09 11:15:00',
            ],
            [
                'expectedHumanTime' => '5 hour',
                'startDateTime' => '2024-04-09 10:15:00',
                'endDateTime' => '2024-04-09 15:15:00',
            ],
            [
                'expectedHumanTime' => '1 day',
                'startDateTime' => '2024-04-09 10:15:00',
                'endDateTime' => '2024-04-10 10:15:00',
            ],
            [
                'expectedHumanTime' => '5 day',
                'startDateTime' => '2024-04-09 10:15:00',
                'endDateTime' => '2024-04-14 10:15:00',
            ],
            [
                'expectedHumanTime' => '1 month',
                'startDateTime' => '2024-04-09 10:15:00',
                'endDateTime' => '2024-05-09 10:15:00',
            ],
            [
                'expectedHumanTime' => '5 month',
                'startDateTime' => '2024-04-09 10:15:00',
                'endDateTime' => '2024-09-09 10:15:00',
            ],
            [
                'expectedHumanTime' => '1 year',
                'startDateTime' => '2024-04-09 10:15:00',
                'endDateTime' => '2025-04-09 10:15:00',
            ],
            [
                'expectedHumanTime' => '5 year',
                'startDateTime' => '2024-04-09 10:15:00',
                'endDateTime' => '2029-04-09 10:15:00',
            ],
            [
                'expectedHumanTime' => '1 day',
                'startDateTime' => '2024-02-29 10:15:00',
                'endDateTime' => '2024-03-01 10:15:00',
            ],
            [
                'expectedHumanTime' => '9 minute 50 second',
                'startDateTime' => '2024-02-29 23:50:10',
                'endDateTime' => '2024-03-01 00:00:00',
            ],
            [
                'expectedHumanTime' => '6 day 11 hour 43 minute 15 second',
                'startDateTime' => '2024-04-09 10:15:00',
                'endDateTime' => '2024-04-15 21:58:15',
            ],
        ];
    }
}
