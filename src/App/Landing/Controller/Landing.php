<?php

namespace App\Landing\Controller;

use Verse\Di\Env;
use Verse\Router\Router;
use Verse\Run\Controller\SimpleController;
use Verse\Run\Spec\AmqpHttpRequest;
use Verse\Run\Util\Uuid;

class Landing extends SimpleController
{
    public function index () : string
    {
        /* @var $router Router */
        $router = Env::getContainer()->bootstrap(Router::class);

        $queueName = 'http-worker';

        $corrId = $router->publish([
            AmqpHttpRequest::METHOD => 'get',
            AmqpHttpRequest::DATA => [],
            AmqpHttpRequest::QUERY => [],
            AmqpHttpRequest::UID => Uuid::v4(),
            AmqpHttpRequest::PATH => '/',
            AmqpHttpRequest::REPLY => $router->getReplyQueueName(),
            AmqpHttpRequest::BORN => microtime(1),
            AmqpHttpRequest::HEADERS => [],
        ], $queueName, true);

        if (!$corrId) {
            return "Error on sending request to queue. ";
        }
        $reply = $router->readResult($queueName, $corrId, 2);
        return json_encode($reply);
    }
}