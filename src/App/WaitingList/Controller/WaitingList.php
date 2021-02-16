<?php


namespace App\WaitingList\Controller;


use App\WaitingList\Service\WaitingListService;
use App\WaitingList\Storage\WaitingListStorage;
use Verse\Telegram\Run\Controller\TelegramResponse;
use Verse\Telegram\Run\Controller\TelegramRunController;

class WaitingList extends TelegramRunController
{
    public function text_message(): ?TelegramResponse
    {
        $records = (new WaitingListService())->listCurrent();

        $names = [];
        foreach ($records as $record) {
            $names[] = $record[WaitingListStorage::USER_ID].' in '.$record[WaitingListStorage::CHAT_ID];
        }

        return $this->response()
            ->setText(implode("\n", $names))
            ->addKeyboardKey('WaitingList', '/waitingList?test=blabla11')
        ;
    }

    public function callback_query () : TelegramResponse
    {
        $testParam = $this->requestWrapper->getParam('test', 'missing');
        return $this->response()->setText('Gotten! '.$testParam);
    }
}