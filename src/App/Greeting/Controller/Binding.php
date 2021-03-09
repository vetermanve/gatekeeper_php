<?php


namespace App\Greeting\Controller;


use App\Context\Storage\GreetingBindingStorage;
use Verse\Telegram\Run\Controller\TelegramResponse;
use Verse\Telegram\Run\Controller\TelegramRunController;

class Binding extends TelegramRunController
{
    public function text_message(): ?TelegramResponse
    {
        $storage = new GreetingBindingStorage();
        $binds = $storage->search()->find([], 100, __METHOD__);

        $chatIds = $binds ? array_column($binds, GreetingBindingStorage::CHAT_ID, GreetingBindingStorage::CHAT_ID) : [];

        $text = '';
        if ($binds) {
            foreach ($binds as $bind) {
                $text .= $bind[GreetingBindingStorage::CHAT_ID]."\n";
            }
        } else {
            $text = 'No greeting binds';
        }


        return $this->textResponse($text);
    }

}