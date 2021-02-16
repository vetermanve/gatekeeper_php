<?php


namespace App\WaitingList\Controller;


use App\WaitingList\Service\WaitingListService;
use Verse\Telegram\Run\Controller\TelegramResponse;
use Verse\Telegram\Run\Controller\TelegramRunController;

class AddMe extends TelegramRunController
{
    public function text_message(): ?TelegramResponse
    {
        $userId = $this->getUpdate()->getMessage()->from->id;
        $chatId = $this->getUpdate()->getChat()->id;

        $rec = (new WaitingListService())->add($userId, $chatId);
        return $this->response()->setText(json_encode($rec));
    }
}