<?php


namespace App\ProcessManager\Run\Schema\Provider;


use Verse\Di\Env;
use Verse\Router\Router;
use Verse\Run\ChannelMessage\ChannelMsg;
use Verse\Run\Provider\RequestProviderProto;
use Verse\Run\RunRequest;
use Verse\Run\Spec\AmqpHttpRequest;
use Verse\Run\Spec\HttpRequestHeaders;
use Verse\Run\Spec\HttpRequestMetaSpec;
use Vetermanve\MultiTaskBackground;

class ProcessManagerProvider extends RequestProviderProto
{
    private array $processes = [];

    private string $entrypoint = '/var/www/update_provider.php';

    private string $commandQueue = '';

    private MultiTaskBackground $runner;

    private Router $router;

    public function prepare()
    {
        $this->runner = new MultiTaskBackground();

        $this->commandQueue = (string)$this->context->getScope('config', 'PROCESS_MANAGER_QUEUE', '');

        $this->processes = [
            'http-amqp-sample' => "AMQP_QUEUE='http-worker' ".$this->entrypoint
        ];

        $this->runner->setProviderCallback([$this,'getActiveTaskList']);
        $this->runner->setCanStopCallback([$this, 'watchMoment']);

        $this->router = Env::getContainer()->bootstrap(Router::class);
    }

    public function getActiveTaskList() {
        return $this->processes;
    }

    public function watchMoment() : bool {
        if (!$this->commandQueue) {
            return true;
        }


        $consumer = $this->router->getConsumer($this->commandQueue);
        while (($command = $consumer->readOne()) !== null) {
//            $this->processCommand($command[AmqpHttpRequest::METHOD], $command[AmqpHttpRequest::DATA]);

            $channelMsg = new ChannelMsg();
            $channelMsg->setBody("All good!");

            $this->getCore()->getDataChannel()->send($channelMsg);
            // create main request object
//            $request = new RunRequest($command[AmqpHttpRequest::UID], $command[AmqpHttpRequest::PATH], $command[AmqpHttpRequest::REPLY]);
//
//            $request->data = $command[AmqpHttpRequest::DATA];
//
//            $request->meta = [
//                HttpRequestMetaSpec::REQUEST_METHOD  => $command[AmqpHttpRequest::METHOD] ?? "GET",
//                HttpRequestMetaSpec::REQUEST_HEADERS => $command[AmqpHttpRequest::HEADERS],
//            ];
//
//            $this->getCore()->getProcessor()->process($request);
        }
    }

    public function processCommand($method, $data) {
        switch ($method) {

        }
    }

    public function run()
    {
        $this->runner->run();
    }

    private function listProcesses() {
        $this->runner->continueProcessing();
    }

    private function startProcess() {

    }

    private function stopProcess() {

    }
}