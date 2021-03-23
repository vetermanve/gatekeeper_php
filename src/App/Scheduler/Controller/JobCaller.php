<?php


namespace App\Scheduler\Controller;


use App\Scheduler\Run\RemoteClient\ExternalRemoteClient;
use Symfony\Contracts\Translation\TranslatorTrait;
use Verse\Run\Controller\SimpleController;
use Verse\Telegram\Run\Controller\TelegramControllerTrait;
use Verse\Telegram\Run\Controller\TelegramResponse;

class JobCaller extends SimpleController
{
    use TelegramControllerTrait;

    public function post() : ?array {
        $queue = $this->p('queue');
        $method = $this->p('method');
        $resource = $this->p('resource');
        $data =  $this->p('data');

        $client = new ExternalRemoteClient();
        $client->setQueueName($queue);
        $result = $client->sendRequest($method, $resource, [], $data);
        return $result->read();
    }

    public function text_message(): ?TelegramResponse
    {
        $text = trim($this->p('text'));
        $params = explode("\n", $text);
        if (count($params) !== 4) {
            return $this->textResponse("Should be 4 parameters: queue, method, resource, data(json), each on new line");
        }

        [$queue, $method, $resource, $dataString] = $params;
        $data = json_decode($dataString, true);
        if (!is_array($data)) {
            return $this->textResponse("Data should be a valid json array or object. " .
                "Even if you want to send nothing. " .
                "If ypu want to send nothing, pass {}");
        }

        $client = new ExternalRemoteClient();
        $client->setQueueName($queue);
        $result = $client->sendRequest($method, $resource, [], $data);
        $data = $result->read();

        if (is_null($data)) {
            return $this->textResponse("Error: ".$result->getError());
        }

        return $this->textResponse(json_encode($result->read()));
    }
}