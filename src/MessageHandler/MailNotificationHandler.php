<?php

namespace App\MessageHandler;

use App\Message\MailNotification;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class MailNotificationHandler
{
    public function __invoke(MailNotification $message)
    {
        // ... do some work - like sending an SMS message!
    }
}