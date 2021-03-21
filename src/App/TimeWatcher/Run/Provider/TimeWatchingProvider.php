<?php


namespace App\TimeWatcher\Run\Provider;


use App\TimeWatcher\Run\RemoteClient\ExternalRemoteClient;
use App\TimeWatcher\Run\Storage\TimeHitStorage;
use Base\RemoteCall\RemoteCallClient;
use Verse\Run\Provider\RequestProviderProto;

class TimeWatchingProvider extends RequestProviderProto
{
    private $interval = 1; //seconds

    private $coldStartDelay = 3600; //seconds

    private TimeHitStorage $timeHitStorage;

    private $method = 'post';

    private $resource = '/';

    private $queueName = 'time-watcher';


    private RemoteCallClient $remoteClient;

    public function prepare()
    {
        $this->timeHitStorage = new TimeHitStorage();

        $this->remoteClient = new ExternalRemoteClient();
        $this->remoteClient->setQueueName($this->queueName);
    }

    public function run()
    {
        $startTime = time();
        $lastStart = $this->timeHitStorage->read()->get('last_start', __METHOD__, null);
        $lastStartTime = !empty($lastStart) && is_array($lastStart) ? $lastStart[TimeHitStorage::TIME] : $startTime - $this->coldStartDelay;

        $timesToNotify = range($lastStartTime, $startTime, $this->interval);
        foreach ($timesToNotify as $i => $time) {
            $this->remoteClient->sendRequest($this->method, $this->resource, [], [
                'time' => $time,
                'sent' => microtime(1),
            ]);
        }

    }
}