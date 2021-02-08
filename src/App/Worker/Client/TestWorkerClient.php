<?php


namespace App\Worker\Client;


use Base\RemoteCall\RemoteCallClient;

class TestWorkerClient extends RemoteCallClient
{

    public function getQueueName(): string
    {
        return 'http-worker';
    }
}