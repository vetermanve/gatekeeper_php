<?php


namespace Verse\Telegram\Controller;


use Verse\Telegram\Service\TelegramUpdatePull;
use Verse\Run\Controller\SimpleController;

class Test extends SimpleController
{
    function get() {
        $puller = new TelegramUpdatePull();

        return $puller->get();
    }
}