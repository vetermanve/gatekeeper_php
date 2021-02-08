<?php


namespace App\Telegram\Run\Processor;


use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\Update;
use Verse\Run\ChannelMessage\ChannelMsg;
use Verse\Run\Processor\RunRequestProcessorProto;
use Verse\Run\RunRequest;

class TelegramUpdateProcessor extends RunRequestProcessorProto
{

    public function prepare()
    {
        // TODO: Implement prepare() method.
    }

    public function process(RunRequest $request)
    {
        /* @var $message Message */
        $message = $request->data;

        var_dump($message);

        $response = new ChannelMsg();
        $response->body = $message->getText();
        $response->setDestination($message->getChat()->getId());

        $this->sendResponse($response, $request);
    }
}