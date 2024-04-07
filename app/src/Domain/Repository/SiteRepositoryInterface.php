<?php

namespace App\Domain\Repository;

use App\Domain\Model\Site;

interface SiteRepositoryInterface
{
    /** @return Site[] */
    public function getAll(): array;

    public function update(Site $site): Site;
}
