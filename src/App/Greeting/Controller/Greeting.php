<?php


namespace App\Greeting\Controller;


use App\Greeting\Storage\GreetingMessageStorage;
use Verse\Telegram\Run\Controller\TelegramResponse;
use Verse\Telegram\Run\Controller\TelegramRunController;

class Greeting extends TelegramRunController
{
    public function text_message(): ?TelegramResponse
    {
        $storage = new GreetingMessageStorage();
        $greetings = $storage->search()->find([], 100, __METHOD__);

        $text = 'No Greetings';

        if ($greetings) {
            foreach ($greetings as $greeting) {
                $text .= $greeting[GreetingMessageStorage::TEXT]."\n\n";
            }
        }

        return $this->textResponse(trim($text));
    }
}