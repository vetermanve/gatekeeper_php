<?php


namespace Verse\Telegram\Run\Controller;


use Psr\Log\LoggerInterface;
use Telegram\Bot\Objects\CallbackQuery;
use Telegram\Bot\Objects\Update;
use Verse\Di\Env;
use Verse\Run\Controller\SimpleController;

/**
 *  Class methods are should be @var \Verse\Telegram\Run\Spec\MessageType constants
 */
abstract class TelegramRunController extends SimpleController
{

    public function text_message() : ?TelegramResponse
    {
        return null;
    }

    public function left_chat_member() : ?TelegramResponse
    {
        return null;
    }

    public function new_chat_members() : ?TelegramResponse
    {
        return null;
    }

    public function edited_message() : ?TelegramResponse
    {
        return $this->text_message();
    }

    public function not_supported() : ?TelegramResponse
    {
        return null;
    }

    public function callback_query() : ?TelegramResponse
    {
        return null;
    }

    public function group_chat_created() : ?TelegramResponse
    {
        return null;
    }

    protected function response() : TelegramResponse {
        return new TelegramResponse();
    }

    public function getUpdate() : ?Update
    {
        return $this->requestWrapper->getParam('update');
    }

    public function log($text, $data = [])
    {
        Env::getContainer()->bootstrap(LoggerInterface::class)->debug($text, $data);
    }

    protected function textResponse(?string $text) : TelegramResponse
    {
        return $this->response()->setText(empty($text) ? "*Empty message error*" : $text);
    }
}