<?php

namespace App\Infrastructure\Repository\FileJson\Site;

use Exception;

class Validator
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
     * @param array{"name": string,
     *      "url": string,
     *      "status": string,
     *      "system_storage_file": string,
     *      "time": string,
     *      "success_code": int} $row
     */
    public function validate(array $row): void
    {
        foreach (self::REQUIRED_FIELDS as $field) {
            if (!array_key_exists($field, $row)) {
                throw new Exception(sprintf('Does not exist field: %s', $field));
            }
        }
    }
}
