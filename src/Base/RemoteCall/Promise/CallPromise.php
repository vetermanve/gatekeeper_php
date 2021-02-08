<?php


namespace Base\RemoteCall\Promise;


use Base\RemoteCall\Promise\PromiseInterface;
use Verse\Router\Router;

class CallPromise implements PromiseInterface
{
    /**
     * @var Router
     */
    private Router $router;

    /**
     * @var float
     */
    private float $timeout;

    /**
     * @var string;
     */
    private string $forwardQueueName;

    /**
     * @var string;
     */
    private string $requestId;

    private string $error = '';

    private bool $isResultRead = false;

    private $result;

    /**
     * CallPromise constructor.
     * @param Router $router
     * @param float $timeout
     * @param string $forwardQueueName
     * @param string $requestId
     */
    public function __construct(Router $router, float $timeout, string $forwardQueueName, string $requestId)
    {
        $this->router = $router;
        $this->timeout = $timeout;
        $this->forwardQueueName = $forwardQueueName;
        $this->requestId = $requestId;
    }

    public function read() {
        if ($this->isResultRead) {
            return $this->result;
        }

        try {
            $this->isResultRead = true;

            $this->result = $this->router->readResult(
                $this->forwardQueueName,
                $this->requestId,
                $this->timeout
            );

            return $this->result;
        } catch (\Throwable $throwable) {
            $this->error = $throwable->getMessage();
        }

        return null;
    }



    /**
     * @param Router $router
     */
    public function setRouter(Router $router): void
    {
        $this->router = $router;
    }

    /**
     * @param float $timeout
     */
    public function setTimeout(float $timeout): void
    {
        $this->timeout = $timeout;
    }

    /**
     * @param string $requestId
     */
    public function setRequestId(string $requestId): void
    {
        $this->requestId = $requestId;
    }

    public function getError() : string
    {
        return $this->error;
    }
}