<?php

namespace App\Infrastructure\Repository\FileJson\Site;

use App\Domain\Model\Site;
use App\Domain\Repository\SiteRepositoryInterface;

class SiteRepositoryFileJson implements SiteRepositoryInterface
{
    private array $sites;

    public function __construct(private FileJsonDataObject $fileJsonDataObject)
    {
        $this->sites = $this->fileJsonDataObject->load();
    }

    public function getAll(): array
    {
        return array_values($this->sites);
    }

    public function update(Site $site): Site
    {
        $this->fileJsonDataObject->update($site->getName(), $site);

        return $site;
    }
}
