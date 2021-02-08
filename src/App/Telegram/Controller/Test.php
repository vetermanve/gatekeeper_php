<?php


namespace App\Telegram\Controller;


use App\Telegram\Service\TelegramUpdatePull;
use Verse\Run\Controller\SimpleController;

class Test extends SimpleController
{
    function get() {
        $puller = new TelegramUpdatePull();

        return $puller->get();
    }
}