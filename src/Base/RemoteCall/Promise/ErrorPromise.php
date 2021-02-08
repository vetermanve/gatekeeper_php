<?php


namespace Base\RemoteCall\Promise;


use Base\RemoteCall\Promise\PromiseInterface;

class ErrorPromise implements PromiseInterface
{
    private string $error;

    /**
     * ErrorPromise constructor.
     * @param string $error
     */
    public function __construct(string $error)
    {
        $this->error = $error;
    }

    public function read()
    {
        return null;
    }

    public function getError() : string
    {
        return $this->error;
    }

    /**
     * @param mixed $error
     */
    public function setError(string $error): void
    {
        $this->error = $error;
    }
}