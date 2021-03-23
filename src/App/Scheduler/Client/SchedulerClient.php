<?php


namespace App\Scheduler\Client;


use Base\RemoteCall\RemoteCallClient;

class SchedulerClient extends RemoteCallClient
{
    const QUEUE_NAME = 'scheduler';

    protected function getClientQueueName(): string
    {
        return self::QUEUE_NAME;
    }
}