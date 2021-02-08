<?php


namespace Base\RemoteCall\Promise;


interface PromiseInterface
{
    public function read();
    public function getError();
}