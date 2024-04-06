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
        private ParserFromJsonToObject $parser
    ) {
        if (!file_exists($this->pathToConfig)) {
            throw new Exception('File storage sites_config.json does not exit');
        }
    }

    public function load(): array
    {
        $data = file_get_contents($this->pathToConfig);
        $rows = json_decode($data,true);

        $this->validateJsonRows($rows);

        $this->pathToFileStorages = $this->getOrCreateFileStorage($rows);

        $result = [];
        foreach ($this->pathToFileStorages as $key => $path) {
            $result[$key] = $this->parseFromJsonToObject($path);
        }
        return $result;
    }


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
     * @param array $rows
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

    private function parseFromJsonToObject(string $path): Site
    {
        $rawSite = json_decode(file_get_contents($path), true);
        $site = $this->parser->handle($rawSite);

        return $site;
    }


}
