<?php
namespace App\Solutions\Controller;

use App\Solutions\Service\SolutionsStorage;
use Base\Render\RendererInterface;
use Verse\Di\Env;
use Verse\Run\Controller\SimpleController;
use Verse\Run\Util\Uuid;

class Search extends SimpleController {

    private $createFields = [
        SolutionsStorage::PATH,
        SolutionsStorage::TYPE,
        SolutionsStorage::NAME,
    ];

    function post() {
        $storage = new SolutionsStorage();

        $filterBind = $this->requestWrapper->getParamsByKeys($this->createFields);

        return array_values($storage->search()->find($filterBind, $this->p('limit', 100), __METHOD__));
    }
}