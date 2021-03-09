<?php


namespace App\Chat\Controller;


use Verse\Telegram\Run\ChannelState\TelegramState;
use Verse\Telegram\Run\Controller\TelegramResponse;
use Verse\Telegram\Run\Controller\TelegramRunController;
use Verse\Telegram\Service\VerseTelegramClient;

class Chat extends TelegramRunController
{
    public function text_message(): ?TelegramResponse
    {
        $chatId = $this->getState(TelegramState::CHAT_ID);
        $this->getState(TelegramState::CHAT_ID);

        $data = $this->getChat($chatId);

        return  $this->textResponse(json_encode($data));
    }

    public function getChat($id) {
        $client = new VerseTelegramClient();
        try {
            return $client->getApi()->getChat(['chat_id' => $id]);
        } catch (\Throwable $exception) {
            return "Error ".$exception->getMessage();
        }
    }
}