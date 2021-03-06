<?php


namespace App\Greeting\Storage;


use Verse\Storage\Data\JBaseDataAdapter;
use Verse\Storage\SimpleStorage;
use Verse\Storage\StorageContext;
use Verse\Storage\StorageDependency;

class GreetingMessageStorage extends SimpleStorage
{
    const ID = 'id';
    const TEXT = 'text';
    const USER_ADDED_BY = 'user_id';

    private $dataDir = '';

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
        $adapter->setDatabase('gatekeeper');
        // set table (folder) name
        $adapter->setResource('greeting_message');

        $container->setModule(StorageDependency::DATA_ADAPTER, $adapter);
    }
}