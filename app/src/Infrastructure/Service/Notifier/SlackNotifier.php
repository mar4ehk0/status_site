<?php

namespace App\Infrastructure\Service\Notifier;

use App\Domain\Service\NotifierInterface;

class SlackNotifier implements NotifierInterface
{
    public function send(string $message): void
    {
        $this->sendToSlack($message);
    }
    private function sendToSlack(string $message): void
    {
        file_put_contents('/tmp/notifier', __METHOD__, FILE_APPEND);
    }
}
