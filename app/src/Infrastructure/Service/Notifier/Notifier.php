<?php

namespace App\Infrastructure\Service\Notifier;

use App\Domain\Service\NotifierInterface;

class Notifier implements NotifierInterface
{

    /**
     * @var NotifierInterface[]
     */
    private array $notifiers;

    public function __construct(NotifierInterface ... $notifiers)
    {
        $this->notifiers = $notifiers;
    }

    public function send(string $message): void
    {
        file_put_contents('/tmp/notifier',"");

        foreach ($this->notifiers as $notifier) {
            $notifier->send($message);
        }
    }
}
