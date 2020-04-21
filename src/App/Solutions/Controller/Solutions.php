<?php
namespace App\Solutions\Controller;

use App\Solutions\Service\SolutionsStorage;
use Base\Render\RendererInterface;
use Verse\Di\Env;
use Verse\Run\Controller\SimpleController;
use Verse\Run\Util\Uuid;

class Solutions extends SimpleController {

    private $createFields = [
        SolutionsStorage::PATH,
        SolutionsStorage::TYPE,
        SolutionsStorage::NAME,
    ];

    private $editFields = [
        SolutionsStorage::PATH,
        SolutionsStorage::TYPE,
        SolutionsStorage::NAME,
    ];

    function get() {
        $storage = new SolutionsStorage();

        $bind = $this->requestWrapper->getParamsByKeys($this->createFields);

        $bind += [
            SolutionsStorage::ID => Uuid::v4(),
            SolutionsStorage::STATUS => 'created',
            'createdAt' => time(),
        ];

        return $storage->write()->insert($bind[SolutionsStorage::ID], $bind, __METHOD__);
    }

    function put() {

    }
}