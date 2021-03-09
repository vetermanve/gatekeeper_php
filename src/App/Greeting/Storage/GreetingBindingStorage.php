<?php


namespace App\Context\Storage;


use Verse\Storage\Data\JBaseDataAdapter;
use Verse\Storage\SimpleStorage;
use Verse\Storage\StorageContext;
use Verse\Storage\StorageDependency;

class GreetingBindingStorage extends SimpleStorage
{
    const ID = 'id'; // chat_id
    const CHAT_ID = 'g_id';
    const GREETING_ID = 'g_id';
    const LANGUAGE = 'lang';
    const ATTACHED_BY = 'user_id';

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
        $adapter->setResource('greeting_bind');

        $container->setModule(StorageDependency::DATA_ADAPTER, $adapter);
    }
}