<?php


namespace App\Telegram\Run\Channel;


use App\Telegram\Service\TelegramUpdatePull;
use Verse\Di\Env;
use Verse\Router\Router;
use Verse\Run\Channel\AmqpReplyChannel;
use Verse\Run\Channel\DataChannelProto;
use Verse\Run\ChannelMessage\ChannelMsg;
use Verse\Run\RunContext;
use Verse\Run\Spec\HttpResponseSpec;

class TelegramReplyChannel extends DataChannelProto
{
    const UID   = 'uid';
    const CODE  = 'code';
    const HEAD  = 'head';
    const BODY  = 'body';
    const STATE = 'state';
    const FROM = 'from';

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
        $this->telegramClient->post($msg->getDestination(), $msg->getBody());

        $this->getCore()->getRuntime()->runtime('TELEGRAM_REPLY_SENT', ['request_id' => $msg->getUid(), 'to' => $msg->getDestination()]);
    }
}