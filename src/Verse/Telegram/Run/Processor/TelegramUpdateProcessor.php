<?php


namespace Verse\Telegram\Run\Processor;


use Verse\Telegram\Run\Channel\TelegramReplyChannel;
use Verse\Telegram\Run\RequestRouter\TelegramRouterByMessageType;
use Telegram\Bot\Objects\CallbackQuery;
use Telegram\Bot\Objects\Message;
use Verse\Run\ChannelMessage\ChannelMsg;
use Verse\Run\Controller\BaseControllerProto;
use Verse\Run\Interfaces\RequestRouterInterface;
use Verse\Run\Processor\RunRequestProcessorProto;
use Verse\Run\RequestWrapper\RunHttpRequestWrapper;
use Verse\Run\RunRequest;

class TelegramUpdateProcessor extends RunRequestProcessorProto
{
    protected string $controllerNamespace = '\\App';

    /**
     * @var RequestRouterInterface
     */
    protected RequestRouterInterface $requestRouter;

    public function prepare()
    {
        if (!isset($this->requestRouter)) {
            $this->requestRouter = new TelegramRouterByMessageType();
        }
    }

    public function process(RunRequest $request)
    {
        $response = new ChannelMsg();
        $response->setUid($request->getUid());
        $response->setBody("Not clear.");
        $response->setDestination($request->getReply());

        $suggestedClass = $this->requestRouter->getClassByRequest($request);
        $class = $this->controllerNamespace.$suggestedClass;
        $this->runtime->runtime('Got Class '.$class);
        if (!class_exists($class)) {
            $response->setBody('Cannot process: Class missing');
            $this->sendResponse($response, $request);
            return;
        }

        $controller = new $class;
        if (!$controller instanceof BaseControllerProto) {
            $response->setBody('Cannot process: Class missing');
            $this->sendResponse($response, $request);
            return;
        }

        $runRequestWrapper = new RunHttpRequestWrapper();
        $runRequestWrapper->setRequest($request);

        $controller->setRequestWrapper($runRequestWrapper);

        if (!$controller->validateMethod()) {
            $response->setBody('Method is not valid');
            $this->sendResponse($response, $request);
            return;
        }

        $responseData = $controller->run();

        if (is_string($responseData)) {
            $response->body = $responseData;
        }

        $this->sendResponse($response, $request);

//        if ($request->getResourcePart(1) === 'message')  {
//            /* @var $message Message */
//            $message = $request->data;
//            $response->body = $message->getText();
//         }
//            $response->setMeta(TelegramReplyChannel::KEYBOARD, [
//                    [
//                        "text" => "A",
//                        "callback_data" => "A1",
//                    ]
//                ]
//            );
//
//        if ($request->getResourcePart(1) === 'callback')  {
//            /* @var $callback CallbackQuery */
//            $callback = $request->data;
//            $response->setDestination($request->getReply());
//        }
    }

    /**
     * @return RequestRouterInterface
     */
    public function getRequestRouter(): RequestRouterInterface
    {
        return $this->requestRouter;
    }

    /**
     * @param RequestRouterInterface $requestRouter
     */
    public function setRequestRouter(RequestRouterInterface $requestRouter): void
    {
        $this->requestRouter = $requestRouter;
    }

    /**
     * @return string
     */
    public function getControllerNamespace(): string
    {
        return $this->controllerNamespace;
    }

    /**
     * @param string $controllerNamespace
     */
    public function setControllerNamespace(string $controllerNamespace): void
    {
        $this->controllerNamespace = $controllerNamespace;
    }
}