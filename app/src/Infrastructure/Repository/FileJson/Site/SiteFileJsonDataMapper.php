<?php

namespace App\Infrastructure\Repository\FileJson\Site;

use App\Domain\Model\Site;
use App\Domain\Model\SiteStatusEnum;
use DateTime;

class SiteFileJsonDataMapper
{
    public function mapFromJsonToObject(string $rawJsonSite): Site
    {
        $rawArraySite = json_decode($rawJsonSite, true, 512, JSON_THROW_ON_ERROR);
        $site = new Site(
            $rawArraySite[EnumFields::NAME],
            $rawArraySite[EnumFields::URL],
            SiteStatusEnum::from($rawArraySite[EnumFields::STATUS]),
            new DateTime($rawArraySite[EnumFields::TIME]),
            (int) $rawArraySite[EnumFields::SUCCESS_CODE],
        );

        return $site;
    }

    public function mapFromObjectToJson(Site $site): string
    {
        $rawArraySite = [
            EnumFields::NAME => $site->getName(),
            EnumFields::URL => $site->getUrl(),
            EnumFields::STATUS => $site->getStatus()->value,
            EnumFields::TIME => $site->getTime()->format(DATE_ATOM),
            EnumFields::SUCCESS_CODE => $site->getSuccessCode(),
        ];

        return json_encode($rawArraySite, JSON_PRETTY_PRINT);
    }
}
