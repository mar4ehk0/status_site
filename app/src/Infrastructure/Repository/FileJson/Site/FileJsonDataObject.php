<?php

namespace App\Infrastructure\Repository\FileJson\Site;

use App\Domain\Model\Site;
use DateTime;
use Exception;

class FileJsonDataObject
{
    private const REQUIRED_FIELDS = [
        EnumFields::NAME => EnumFields::NAME,
        EnumFields::URL => EnumFields::URL,
        EnumFields::STATUS => EnumFields::STATUS,
        EnumFields::SYSTEM_STORAGE_FILE => EnumFields::SYSTEM_STORAGE_FILE,
        EnumFields::TIME => EnumFields::TIME,
        EnumFields::SUCCESS_CODE => EnumFields::SUCCESS_CODE
    ];

    /**
     * @var string[]
     */
    private array $pathToFileStorages;

    public function __construct(
        private string $pathToStorage,
        private string $pathToConfig,
        private SiteFileJsonDataMapper $fileJsonDataMapper
    ) {
        if (!file_exists($this->pathToConfig)) {
            throw new Exception('File storage sites_config.json does not exit');
        }
    }

    /**
     * @return array<string, Site>
     * @throws Exception
     */
    public function load(): array
    {
        $data = file_get_contents($this->pathToConfig);
        $rows = json_decode($data, true);

        $this->validateJsonRows($rows);

        $this->pathToFileStorages = $this->getOrCreateFileStorage($rows);

        $result = [];
        foreach ($this->pathToFileStorages as $key => $path) {
            $result[$key] = $this->readFromFileStorage($path);
        }
        return $result;
    }

    public function add(Site $site): void
    {
        // @todo implements
        throw new Exception('Does not implement ' . __METHOD__);
    }

    public function update(string $siteName, Site $site): void
    {
        if (!array_key_exists($siteName, $this->pathToFileStorages)) {
            throw new Exception(sprintf('Does not exit file for site: %s. Please call method add.', $siteName));
        }

        $pathToFileStorage = $this->pathToFileStorages[$siteName];

        $this->writeToFileStorage($pathToFileStorage, $site);
    }

    /**
     * @param array<array{"name": string, "url": string, "status": string, "system_storage_file": string, "time": string, "success_code": int}> $rows
     */
    private function validateJsonRows(array $rows): void
    {
        foreach (self::REQUIRED_FIELDS as $field) {
            foreach ($rows as $key => $row) {
                if (!array_key_exists($field, $row)) {
                    throw new Exception(sprintf('Does not exist field: %s in site: %d', $field, $key));
                }
            }
        }
    }

    /**
     * @param array<array{"name": string, "url": string, "status": string, "system_storage_file": string, "time": string, "success_code": int}> $rows
     * @return string[]
     */
    private function getOrCreateFileStorage(array $rows): array
    {
        $result = [];
        foreach ($rows as $row) {
            $pathFileStorage = $this->pathToStorage . $row[self::REQUIRED_FIELDS[EnumFields::SYSTEM_STORAGE_FILE]];
            if (!file_exists($pathFileStorage)) {
                unset($row[self::REQUIRED_FIELDS[EnumFields::SYSTEM_STORAGE_FILE]]);
                $row[self::REQUIRED_FIELDS[EnumFields::TIME]] = (new DateTime())->format(DATE_ATOM);
                file_put_contents($pathFileStorage, json_encode($row, JSON_PRETTY_PRINT));
            }
            $result[$row[self::REQUIRED_FIELDS[EnumFields::NAME]]] = $pathFileStorage;
        }

        return $result;
    }

    private function readFromFileStorage(string $path): Site
    {
        $rawSite = file_get_contents($path);
        return $this->fileJsonDataMapper->mapFromJsonToObject($rawSite);
    }

    private function writeToFileStorage(string $path, Site $site): void
    {
        $siteJson = $this->fileJsonDataMapper->mapFromObjectToJson($site);
        file_put_contents($path, $siteJson);
    }
}
