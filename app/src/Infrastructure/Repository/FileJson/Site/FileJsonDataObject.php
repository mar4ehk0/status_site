<?php

namespace App\Infrastructure\Repository\FileJson\Site;

use App\Domain\Model\Site;
use DateTime;
use Exception;

class FileJsonDataObject
{
    /**
     * @var string[]
     */
    private array $pathToFileStorages;

    public function __construct(
        private string $pathToStorage,
        private string $pathToConfig,
        private SiteFileJsonDataMapper $fileJsonDataMapper,
        private Validator $validator,
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
        $this->validateConfigRows($rows);

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
     * @param array<array{"name": string,
     *      "url": string,
     *      "status": string,
     *      "system_storage_file": string,
     *      "time": string,
     *      "success_code": int}> $rows
     */
    private function validateConfigRows(array $rows): void
    {
        foreach ($rows as $row) {
            $this->validator->validate($row);
        }
    }

    /**
     * @param array<array{"name": string,
     *       "url": string,
     *       "status": string,
     *       "system_storage_file": string,
     *       "time": string,
     *       "success_code": int}> $rows
     * @return string[]
     */
    private function getOrCreateFileStorage(array $rows): array
    {
        $result = [];
        foreach ($rows as $row) {
            $pathFileStorage = $this->pathToStorage . $row[EnumFields::SYSTEM_STORAGE_FILE];
            if (!file_exists($pathFileStorage)) {
                unset($row[EnumFields::SYSTEM_STORAGE_FILE]);
                $row[EnumFields::TIME] = (new DateTime())->format(DATE_ATOM);
                file_put_contents($pathFileStorage, json_encode($row, JSON_PRETTY_PRINT));
            }
            $result[$row[EnumFields::NAME]] = $pathFileStorage;
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
