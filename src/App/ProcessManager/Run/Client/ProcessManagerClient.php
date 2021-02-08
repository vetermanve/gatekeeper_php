<?php


namespace App\ProcessManager\Run\Schema\Client;


use Base\RemoteCall\RemoteCallClient;
use Verse\Di\Env;
use Verse\Modular\ModularContextProto;

class ProcessManagerClient extends RemoteCallClient
{
    public const DEFAULT_REMOTE_PROCESS_MANAGER = 'process-manager';

    public function getQueueName(): string
    {
        /* @var $config ModularContextProto */
        $config =  Env::getContainer()->bootstrap('config');
        return $config->get('PROCESS_MANAGER_QUEUE', self::DEFAULT_REMOTE_PROCESS_MANAGER);
    }
}