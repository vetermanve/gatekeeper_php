<?php


namespace App\Greeting\Controller;


use App\Greeting\Storage\GreetingMessageStorage;
use Verse\Run\Util\Uuid;
use Verse\Telegram\Run\ChannelState\TelegramState;
use Verse\Telegram\Run\Controller\TelegramResponse;
use Verse\Telegram\Run\Controller\TelegramRunController;

class Add extends TelegramRunController
{
    public function text_message(): ?TelegramResponse
    {
        $text = trim($this->p('text'));

        if (empty($text)) {
            return $this->textResponse('No greeting passed');
        }

        $id = Uuid::v4();
        $bind = [
            GreetingMessageStorage::TEXT => $text,
            GreetingMessageStorage::USER_ADDED_BY => $this->getState(TelegramState::USER_ID)
        ];

        $storage = new GreetingMessageStorage();
        $res = $storage->write()->insert($id, $bind, __METHOD__);

        return $this->textResponse(json_encode($res));
    }
}