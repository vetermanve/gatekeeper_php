<?php


namespace App\Telegram\Service;


use Telegram\Bot\Api;
use Verse\Di\Env;
use Verse\Run\RunContext;

class TelegramUpdatePull
{
    public $token = '';

    public function __construct()
    {
        /** @var RunContext $config */
        $config = Env::getContainer()->bootstrap('config');
        $this->token = $config->get("BOT_TOKEN");
    }

    /**
     * @return mixed|string|null
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed|string|null $token
     */
    public function setToken($token): void
    {
        $this->token = $token;
    }

    public function get($offset = 0, $limit = 100)
    {
        $telegram = new Api($this->token);

        $response = $telegram->getUpdates([
            'limit' => $limit,
            'offset' => $offset,
            'allowed_updates' => json_encode(['message'])
        ]);

        return $response;
    }

    /**
     * @param $chatId
     * @param $text
     * @param int $replyToMessageId
     * @return \Telegram\Bot\Objects\Message
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function post($chatId, $text, $replyToMessageId = 0)
    {
        $telegram = new Api($this->token);

        $message = $telegram->sendMessage(
            $params = [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'html',
                'disable_web_page_preview' => '1',
                'reply_to_message_id' => $replyToMessageId,
                #'reply_markup'             => '',
            ]
        );

        return $message;
    }
}