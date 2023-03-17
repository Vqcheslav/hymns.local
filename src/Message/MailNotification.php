<?php

namespace App\Message;

class MailNotification
{
    private string $content;

    public function __construct()
    {

    }

    public function getContent(): string
    {
        return $this->content;
    }
}