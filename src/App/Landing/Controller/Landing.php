<?php

namespace App\Landing\Controller;

use App\Landing\Service\GreetingTextProvider;
use App\Worker\Client\TestWorkerClient;
use Verse\Telegram\Run\Controller\TelegramResponse;
use Verse\Telegram\Run\Controller\TelegramRunController;

class Landing extends TelegramRunController
{
    public function get() {
        return $this->index();
    }

    public function text_message() : TelegramResponse {
        $text =  $this->index();

        $response = new TelegramResponse();
        $response->setText($text);

        return $response;
    }

    public function index() : string {
        $client = new TestWorkerClient();
        $result = $client->sendRequest('get','/worker-sample');

        if ($result->read()) {
            return json_encode($result->read());
        }

        return 'Error: '.$result->getError();
    }

    public function new_chat_members() : TelegramResponse {
        $newMembers = $this->requestWrapper->getParams();

        $textProvider = new GreetingTextProvider();

        $response = new TelegramResponse();
        $response->setText($textProvider->getText(0, $newMembers));

        return $response;
    }

    public function group_chat_created(): ?TelegramResponse
    {
        return $this->textResponse("Всем привет в этом чате! Давайте настроимся!");
    }
}