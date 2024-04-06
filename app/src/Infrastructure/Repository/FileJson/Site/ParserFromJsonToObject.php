<?php

namespace App\Infrastructure\Repository\FileJson\Site;

use App\Domain\Model\Site;
use App\Domain\Model\SiteStatusEnum;
use DateTime;

class ParserFromJsonToObject
{
    public function handle(array $rawSite): Site
    {
        $site = new Site(
            $rawSite[EnumFields::NAME],
            $rawSite[EnumFields::URL],
            SiteStatusEnum::from($rawSite[EnumFields::STATUS]),
            new DateTime($rawSite[EnumFields::TIME]),
            (int) $rawSite[EnumFields::SUCCESS_CODE],
        );

        return $site;
    }
}
