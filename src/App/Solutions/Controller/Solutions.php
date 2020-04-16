<?php
namespace App\Solutions\Controller;

use Base\Render\RendererInterface;
use Verse\Di\Env;
use Verse\Run\Controller\SimpleController;

class Solutions extends SimpleController {

    function get() {
        return ['Yes'];
    }
}