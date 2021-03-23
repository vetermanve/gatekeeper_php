<?php


namespace App\Scheduler\Run\RemoteClient;


use Base\RemoteCall\RemoteCallClient;

class ExternalRemoteClient extends RemoteCallClient
{


    public function getQueueName(): string
    {
        return $this->queueName;
    }

    /**
     * @param string $queueName
     */
    public function setQueueName(string $queueName): void
    {
        $this->queueName = $queueName;
    }


    protected function getClientQueueName(): string
    {
        return '';
    }
}