<?php

namespace App\Infrastructure\Repository\FileJson\Site;

use App\Domain\Repository\SiteRepositoryInterface;

class SiteRepositoryFileJson implements SiteRepositoryInterface
{
    private array $sites;

    public function __construct(private FileJsonDataObject $loader)
    {
        $this->sites = $this->loader->load();
    }

    public function getAll(): array
    {
        return array_values($this->sites);
    }
}
