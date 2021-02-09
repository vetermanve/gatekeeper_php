<?php


namespace App\Telegram\Run\Processor;


use App\Telegram\Run\Channel\TelegramReplyChannel;
use Telegram\Bot\Objects\CallbackQuery;
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
        $response = new ChannelMsg();
        $response->setUid($request->getUid());
        $response->setBody("Not clear.");

        if ($request->getResourcePart(1) === 'message')  {
            /* @var $message Message */
            $message = $request->data;
            $response->body = $message->getText();
            $response->setDestination($request->getReply());

            $response->setMeta(TelegramReplyChannel::KEYBOARD, [
                    [
                        "text" => "A",
                        "callback_data" => "A1",
                    ]
                ]
            );
        }

        if ($request->getResourcePart(1) === 'callback')  {
            /* @var $callback CallbackQuery */
            $callback = $request->data;
            $response->body = "Tapped: ".$callback->data;
            $response->setDestination($request->getReply());
        }

        $this->sendResponse($response, $request);
    }
}