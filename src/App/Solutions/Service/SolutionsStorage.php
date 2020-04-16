<?php


namespace App\Solutions\Service;


use Verse\Storage\Data\JBaseDataAdapter;
use Verse\Storage\SimpleStorage;
use Verse\Storage\StorageContext;
use Verse\Storage\StorageDependency;

class SolutionsStorage extends SimpleStorage
{

    public function loadConfig()
    {

    }

    public function customizeDi(StorageDependency $container, StorageContext $context)
    {
        $adapter = new JBaseDataAdapter();
        $adapter->setDataRoot('/var/www/');
        $adapter->setDatabase('data');

        $container->setModule(StorageDependency::DATA_ADAPTER, $adapter);
    }
}