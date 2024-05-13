<?php

namespace Infrastructure\Repository\FileJson\Site;

use App\Domain\Model\Site;
use App\Domain\Model\SiteStatusEnum;
use App\Infrastructure\Repository\FileJson\Site\SiteFileJsonDataMapper;
use DateTime;
use JsonException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class SiteFileJsonDataMapperTest extends TestCase
{
    public function testFailMapFromJsonToObjectWhenStringIsNotJson(): void
    {
        $mapper = new SiteFileJsonDataMapper();
        $rawJson = '{"name":"site1","url":"http://example.com","status":"up","time":"2024-04-06 12:10:00", success_code":"200"}';

        $this->expectException(JsonException::class);

        $mapper->mapFromJsonToObject($rawJson);
    }

    public function testCanMapFromJsonToObject(): void
    {
        // Arrange
        $mapper = new SiteFileJsonDataMapper();
        $rawJson = '{"name":"site1","url":"http://example.com","status":"up","time":"2024-04-06 12:10:00","success_code":"200"}';

        // Act
        $site = $mapper->mapFromJsonToObject($rawJson);

        // Assert
        $this->assertEquals('site1', $site->getName());
        $this->assertEquals(SiteStatusEnum::Up, $site->getStatus());
        $this->assertEquals('http://example.com', $site->getUrl());
        $this->assertEquals(new DateTime("2024-04-06 12:10:00"), $site->getTime());
        $this->assertEquals(200, $site->getSuccessCode());
    }

    public function testCanMapFromObjectToJson(): void
    {
        // Arrange
        $mapper = new SiteFileJsonDataMapper();
        $time = DateTime::createFromFormat("Y-m-d H:i:s", "2024-04-06 12:10:00");
        $status = SiteStatusEnum::Down;
        $site = new Site('site1', 'http://example.com', $status, $time, 200);

        // Act
        $rawJson = $mapper->mapFromObjectToJson($site);
        $rawArray = json_decode($rawJson, true, 512, JSON_THROW_ON_ERROR);

        // Assert
        $this->assertEquals('site1', $rawArray['name']);
        $this->assertEquals('down', $rawArray['status']);
        $this->assertEquals('http://example.com', $rawArray['url']);
        $this->assertEquals('2024-04-06T12:10:00+00:00', $rawArray['time']);
        $this->assertEquals(200, $rawArray['success_code']);
    }
}
