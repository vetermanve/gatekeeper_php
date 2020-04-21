<?php


namespace App\Solutions\Service;


use Verse\Storage\Data\JBaseDataAdapter;
use Verse\Storage\SimpleStorage;
use Verse\Storage\StorageContext;
use Verse\Storage\StorageDependency;

class SolutionsStorage extends SimpleStorage
{
    /**
     * Id of the solution configuration
     */
    const ID = 'id';

    /**
     * Name of configuration
     */
    const NAME = 'name';

    /**
     * Type of solution configuration
     */
    const TYPE = 'type';

    /**
     * Status of configuration
     */
    const STATUS = 'status';

    /**
     * where to find config - url of fs
     */
    const PATH = 'path';

    /**
     * On which thread of changes is solution is
     */
    const BRANCH = 'branch';



    public function loadConfig()
    {

    }

    public function customizeDi(StorageDependency $container, StorageContext $context)
    {
        $adapter = new JBaseDataAdapter();
        $adapter->setDataRoot('/var/www/data');
        $adapter->setDatabase('ohra');
        $adapter->setResource('solutions');

        $container->setModule(StorageDependency::DATA_ADAPTER, $adapter);
    }
}