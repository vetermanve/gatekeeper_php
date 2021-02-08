<?php


namespace App\ProcessManager\Controller;


use App\ProcessManager\Run\Schema\Client\ProcessManagerClient;
use Verse\Run\Controller\SimpleController;

class ProcessManager extends SimpleController
{
    public function index() : string {
        $client = new ProcessManagerClient();
        $reply = $client->sendRequest('list');
        if ($reply->read()) {
            return json_encode($reply->read());
        }

        return  $reply->getError();
    }
}