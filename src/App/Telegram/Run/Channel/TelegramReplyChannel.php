<?php


namespace App\Telegram\Run\Channel;


use App\Telegram\Service\TelegramUpdatePull;
use Verse\Run\Channel\DataChannelProto;
use Verse\Run\ChannelMessage\ChannelMsg;
use Verse\Run\RunContext;

class TelegramReplyChannel extends DataChannelProto
{
    const KEYBOARD = 'keyboard';

    /**
     * @var TelegramUpdatePull
     */
    private $telegramClient;

    private $identity;

    public function prepare()
    {
        $this->telegramClient = new TelegramUpdatePull();
        $this->identity = $this->context->get(RunContext::IDENTITY);
    }

    public function send(ChannelMsg $msg)
    {
        $keyboard = $msg->getMeta(self::KEYBOARD, []);

        $this->telegramClient->post($msg->getDestination(), $msg->getBody(), $keyboard);

        $this->getCore()->getRuntime()->runtime('TELEGRAM_REPLY_SENT', ['request_id' => $msg->getUid(), 'to' => $msg->getDestination()]);
    }
}