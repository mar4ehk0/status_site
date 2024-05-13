<?php

namespace Infrastructure;

use App\Infrastructure\DateTimeInterval;
use DateTime;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
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
                'expectedHumanTime' => '1 секунда',
                'startDateTime' => '2024-04-09 10:15:00',
                'endDateTime' => '2024-04-09 10:15:01',
            ],
            [
                'expectedHumanTime' => '5 секунда',
                'startDateTime' => '2024-04-09 10:15:00',
                'endDateTime' => '2024-04-09 10:15:05',
            ],
            [
                'expectedHumanTime' => '1 минута',
                'startDateTime' => '2024-04-09 10:15:00',
                'endDateTime' => '2024-04-09 10:16:00',
            ],
            [
                'expectedHumanTime' => '5 минута',
                'startDateTime' => '2024-04-09 10:15:00',
                'endDateTime' => '2024-04-09 10:20:00',
            ],
            [
                'expectedHumanTime' => '1 час',
                'startDateTime' => '2024-04-09 10:15:00',
                'endDateTime' => '2024-04-09 11:15:00',
            ],
            [
                'expectedHumanTime' => '5 час',
                'startDateTime' => '2024-04-09 10:15:00',
                'endDateTime' => '2024-04-09 15:15:00',
            ],
            [
                'expectedHumanTime' => '1 день',
                'startDateTime' => '2024-04-09 10:15:00',
                'endDateTime' => '2024-04-10 10:15:00',
            ],
            [
                'expectedHumanTime' => '5 день',
                'startDateTime' => '2024-04-09 10:15:00',
                'endDateTime' => '2024-04-14 10:15:00',
            ],
            [
                'expectedHumanTime' => '1 месяц',
                'startDateTime' => '2024-04-09 10:15:00',
                'endDateTime' => '2024-05-09 10:15:00',
            ],
            [
                'expectedHumanTime' => '5 месяц',
                'startDateTime' => '2024-04-09 10:15:00',
                'endDateTime' => '2024-09-09 10:15:00',
            ],
            [
                'expectedHumanTime' => '1 год',
                'startDateTime' => '2024-04-09 10:15:00',
                'endDateTime' => '2025-04-09 10:15:00',
            ],
            [
                'expectedHumanTime' => '5 год',
                'startDateTime' => '2024-04-09 10:15:00',
                'endDateTime' => '2029-04-09 10:15:00',
            ],
            [
                'expectedHumanTime' => '1 день',
                'startDateTime' => '2024-02-29 10:15:00',
                'endDateTime' => '2024-03-01 10:15:00',
            ],
            [
                'expectedHumanTime' => '9 минута 50 секунда',
                'startDateTime' => '2024-02-29 23:50:10',
                'endDateTime' => '2024-03-01 00:00:00',
            ],
            [
                'expectedHumanTime' => '6 день 11 час 43 минута 15 секунда',
                'startDateTime' => '2024-04-09 10:15:00',
                'endDateTime' => '2024-04-15 21:58:15',
            ],
        ];
    }
}
