<?php


namespace App\Scheduler\Controller;


use Verse\Run\Controller\SimpleController;
use Verse\Telegram\Run\Controller\TelegramControllerTrait;

class Job extends SimpleController
{
    use TelegramControllerTrait;


}