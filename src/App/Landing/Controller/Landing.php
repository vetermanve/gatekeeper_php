<?php

namespace App\Landing\Controller;

use Verse\Di\Env;
use Verse\Router\Router;
use Verse\Run\Controller\SimpleController;
use Verse\Run\Spec\AmqpHttpRequest;
use Verse\Run\Util\Uuid;

class Landing extends SimpleController
{
    public function get() {
        return "rest-index";
    }

    public function index () : string
    {
        /* @var $router Router */
        $router = Env::getContainer()->bootstrap(Router::class);

        $queueName = 'http-worker';

        $requestId = Uuid::v4();
        $isSuccess = (bool)$router->publish([
            AmqpHttpRequest::METHOD => 'get',
            AmqpHttpRequest::DATA => [],
            AmqpHttpRequest::QUERY => [],
            AmqpHttpRequest::UID => $requestId,
            AmqpHttpRequest::PATH => '/worker-sample',
            AmqpHttpRequest::REPLY => $router->getReplyQueueName(),
            AmqpHttpRequest::BORN => microtime(1),
            AmqpHttpRequest::HEADERS => [],
        ], $queueName, true, [
            'correlation_id' => $requestId
        ]);

        if (!$isSuccess) {
            return "Error on sending request to queue. ";
        }

        $reply = $router->readResult($queueName, $requestId, 1);

        return $requestId . ' ' .json_encode($reply);
    }
}