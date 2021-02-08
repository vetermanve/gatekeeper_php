<?php


namespace Base\Run\Processor\Modificator;


use Verse\Run\ChannelMessage\ChannelMsg;
use Verse\Run\ChannelMessage\HttpReply;
use Verse\Run\Interfaces\MessageModificator;
use Verse\Run\RunRequest;
use Verse\Run\Spec\HttpResponseSpec;

class ClearHeadersMessageModificator implements MessageModificator
{

    public function process(RunRequest $request, ChannelMsg $message)
    {
        $message->setMeta(HttpResponseSpec::META_HTTP_HEADERS, []);
    }
}