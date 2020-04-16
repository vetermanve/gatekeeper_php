<?php
namespace App\Solutions\Controller;

use App\Solutions\Service\SolutionsStorage;
use Base\Render\RendererInterface;
use Verse\Di\Env;
use Verse\Run\Controller\SimpleController;
use Verse\Run\Util\Uuid;

class Solutions extends SimpleController {

    function get() {
        $storage = new SolutionsStorage();

        $storage->write()->insert(Uuid::v4(), [
            'test' => time(),
        ], __METHOD__);

        return $storage->search()->find([],100, __METHOD__);
    }
}