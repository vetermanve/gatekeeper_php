<?php


namespace Base\Run;


use Verse\Run\ChannelMessage\ChannelMsg;
use Verse\Run\Processor\SimplePageProcessor;
use Verse\Run\Processor\SimpleRestProcessor;
use Verse\Run\RunRequest;

class RoutingProcessor extends \Verse\Run\Processor\RunRequestProcessorProto
{
    /**
     * @var SimpleRestProcessor
     */
    protected $apiProcessor;

    /**
     * @var SimplePageProcessor
     */
    protected $pageProcessor;

    public function prepare()
    {
        $this->apiProcessor = new SimpleRestProcessor();
        $this->apiProcessor->follow($this);
        $this->apiProcessor->prepare();

        $this->pageProcessor = new SimplePageProcessor();
        $this->pageProcessor->follow($this);
        $this->pageProcessor->prepare();
    }

    public function process(RunRequest $request)
    {
        if ($request->getResource() === '/' || $request->getResourcePart(0) === 'landing') {
            $this->pageProcessor->process($request);
            return;
        }

        $this->apiProcessor->process($request);
    }
}