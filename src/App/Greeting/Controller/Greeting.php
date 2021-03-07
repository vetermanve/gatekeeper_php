<?php


namespace App\Greeting\Controller;


use Verse\Telegram\Run\Controller\TelegramResponse;
use Verse\Telegram\Run\Controller\TelegramRunController;

class Greeting extends TelegramRunController
{
    public function text_message(): ?TelegramResponse
    {
        return $this->textResponse("This is greeting");
    }
}