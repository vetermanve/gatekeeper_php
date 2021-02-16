<?php


namespace App\WaitingList\Storage;


use Verse\Storage\Data\JBaseDataAdapter;
use Verse\Storage\SimpleStorage;
use Verse\Storage\StorageContext;
use Verse\Storage\StorageDependency;

class WaitingListStorage extends SimpleStorage
{

    const ID = 'id';
    const USER_ID = 'u_id';
    const CHAT_ID = 'c_id';
    const TIME = 't';

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
        $adapter->setResource('waiting-list');

        $container->setModule(StorageDependency::DATA_ADAPTER, $adapter);
    }
}