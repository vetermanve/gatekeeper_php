<?php


namespace Base\RemoteCall;


use Base\RemoteCall\Promise\CallPromise;
use Base\RemoteCall\Promise\ErrorPromise;
use Base\RemoteCall\Promise\PromiseInterface;
use Verse\Di\Env;
use Verse\Router\Router;
use Verse\Run\Spec\AmqpHttpRequest;
use Verse\Run\Util\Uuid;

abstract class RemoteCallClient
{
    protected string $queueName;

    /**
     * @var ?Router
     */
    private ?Router $router;
    private float $replyTimeout = 1;

    /**
     * RemoteCallClient constructor.
     * @param string $queueName
     */
    public function __construct()
    {
        $this->queueName = $this->getClientQueueName();
    }

    private function getRouter() : Router {
        if (!isset($this->router)) {
            $this->router = Env::getContainer()->bootstrap(Router::class);
        }

        return $this->router;
    }

    abstract protected function getClientQueueName() : string;

    public function sendRequest(string $method, string $path = '/', $query = [], $data = []) : PromiseInterface {
        $requestId = Uuid::v4();

        $isSuccess = (bool)$this->getRouter()->publish([
            AmqpHttpRequest::METHOD => $method,
            AmqpHttpRequest::DATA => $data,
            AmqpHttpRequest::QUERY => $query,
            AmqpHttpRequest::UID => $requestId,
            AmqpHttpRequest::PATH => $path,
            AmqpHttpRequest::REPLY => $this->getRouter()->getReplyQueueName(),
            AmqpHttpRequest::BORN => microtime(1),
        ], $this->queueName, true, [
            'correlation_id' => $requestId
        ]);

        if (!$isSuccess) {
            return new ErrorPromise("Error on sending request to queue. ");
        }

        return new CallPromise(
            $this->router,
            $this->replyTimeout,
            $this->queueName,
            $requestId
        );
    }
}