<?php


namespace App\Context\Controller;


use Telegram\Bot\TelegramClient;
use Verse\Telegram\Run\ChannelState\TelegramState;
use Verse\Telegram\Run\Controller\TelegramResponse;
use Verse\Telegram\Run\Controller\TelegramRunController;
use Verse\Telegram\Service\VerseTelegramClient;

class Context extends TelegramRunController
{
    public function text_message(): ?TelegramResponse
    {
//        $chatId = $this->getState(TelegramState::CHAT_ID);
        $chatId = $this->p('text');

//        $this->log("chatId", ['id' => $chatId]);
        $this->log('params', $this->requestWrapper->getParams());

        if (!$chatId) {
            return $this->response()->setText('ChatId not found');
        }

        $data = $this->getChat($chatId);

        return $this->textResponse(json_encode($data));
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