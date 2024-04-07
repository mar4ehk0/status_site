<?php

namespace App\Infrastructure\Service\Notifier;

use App\Domain\Service\NotifierInterface;

class EmailNotifier implements NotifierInterface
{
    public function send(string $message): void
    {
        $this->sendToEmail($message);
    }
    private function sendToEmail(string $message)
    {
        file_put_contents('/tmp/notifier', __METHOD__, FILE_APPEND);
    }
}
