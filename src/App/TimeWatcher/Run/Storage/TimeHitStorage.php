<?php


namespace App\TimeWatcher\Run\Storage;


use Verse\Storage\Data\JBaseDataAdapter;
use Verse\Storage\SimpleStorage;
use Verse\Storage\StorageContext;
use Verse\Storage\StorageDependency;

class TimeHitStorage extends SimpleStorage
{
    private $dataDir = '';

    public const ID = 'id';
    public const TIME = 't';

    public function loadConfig()
    {
        $this->dataDir = getcwd().'/data/storage';
    }

    public function customizeDi(StorageDependency $container, StorageContext $context)
    {
        $adapter = new JBaseDataAdapter();

        // set data location
        $adapter->setDataRoot($this->dataDir);
        // set database (folder) name
        $adapter->setDatabase('time-watcher');
        // set table (folder) name
        $adapter->setResource('time-tracking');

        $container->setModule(StorageDependency::DATA_ADAPTER, $adapter);
    }
}