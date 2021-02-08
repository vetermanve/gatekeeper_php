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
            'allowed_updates' => json_encode(['message', 'callback_query'])
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
    public function post($chatId, $text, $keyboard = [], $replyToMessageId = 0)
    {
        $telegram = new Api($this->token);
        $params = [
            'chat_id' => $chatId,
            'text' => is_string($text) ? $text : json_encode($text),
            #'parse_mode' => 'html',
            'disable_web_page_preview' => '1',
            #'reply_to_message_id' => $replyToMessageId
        ];

        if (!empty($keyboard)) {
            $params['reply_markup'] = json_encode(['inline_keyboard' => [$keyboard]]);
        }

        $message = $telegram->sendMessage($params);

        return $message;
    }
}