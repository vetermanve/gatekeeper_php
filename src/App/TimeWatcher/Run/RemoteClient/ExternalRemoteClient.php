<?php


namespace App\TimeWatcher\Run\RemoteClient;


use Base\RemoteCall\RemoteCallClient;

class ExternalRemoteClient extends RemoteCallClient
{
    public function getQueueName(): string
    {
        return '';
    }

    /**
     * @param string $queueName
     */
    public function setQueueName(string $queueName): void
    {
        $this->queueName = $queueName;
    }


}