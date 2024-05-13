<?php

namespace App\Infrastructure\Exception;

use Exception;

class TransportException extends Exception
{
    public function __construct(string $url)
    {
        $message = sprintf('Request to URL: %s timed out', $url);
        parent::__construct($message);
    }
}
