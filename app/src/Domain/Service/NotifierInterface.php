<?php

namespace App\Domain\Service;

interface NotifierInterface
{
    public function send(string $message): void;
}
