<?php


namespace Verse\Telegram\Run\RequestRouter;


use Verse\Run\Interfaces\RequestRouterInterface;
use Verse\Run\RunRequest;

class TelegramRouterByMessageType implements RequestRouterInterface
{
    const DEFAULT_APPLICATION = 'Landing';
    const DEFAULT_CONTROLLER = 'Landing';

    public function getClassByRequest(RunRequest $request)
    {
        return '\\'.self::DEFAULT_APPLICATION.'\\Controller\\'.self::DEFAULT_CONTROLLER;
    }
}