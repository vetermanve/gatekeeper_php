<?php


namespace App\Telegram\Run\Channel;


use App\Telegram\Run\Spec\MessageType;
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

        [$channel, $chatId, $messageType, $entityId] = explode(':', $msg->getDestination());

        if ($channel !== 'tg') {
            $this->runtime->error('Channel massage came with wrong destination', ['dest' => $msg->getDestination()]);
            return false;
        }

        if ($messageType === MessageType::CALLBACK_QUERY) {
             $this->telegramClient->answerCallback($msg->getBody(), $entityId);
            $this->runtime->debug('TELEGRAM_REPLY_SENT:CALLBACK', ['request_id' => $msg->getUid(), 'to' => $msg->getDestination()]);
            return true;
        } elseif ($messageType === MessageType::MESSAGE) {
            $this->telegramClient->post($chatId, $msg->getBody(), $keyboard, $entityId);
            $this->runtime->debug('TELEGRAM_REPLY_SENT:MESSAGE', ['request_id' => $msg->getUid(), 'to' => $msg->getDestination()]);
            return true;
        }

        $this->runtime->error('TELEGRAM_REPLY_NOT_SENT UNKNOWN MESSAGE REPLY TYPE', ['request_id' => $msg->getUid(), 'to' => $msg->getDestination()]);
        return false;
    }
}