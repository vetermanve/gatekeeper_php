<?php


namespace App\Worker\Client;


use Base\RemoteCall\RemoteCallClient;

class TestWorkerClient extends RemoteCallClient
{

    public function getClientQueueName(): string
    {
        return 'http-worker';
    }
}