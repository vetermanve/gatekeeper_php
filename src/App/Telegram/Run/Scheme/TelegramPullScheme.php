<?php


namespace App\Telegram\Run\Scheme;


use App\Telegram\Run\Channel\TelegramReplyChannel;
use App\Telegram\Run\Processor\TelegramUpdateProcessor;
use App\Telegram\Run\Provider\TelegramGetUpdatesProvider;
use Verse\Run\Component\CreateDependencyContainer;
use Verse\Run\Component\UnexpectedShutdownHandler;
use Verse\Run\Processor\SimpleRestProcessor;
use Verse\Run\Schema\PreconfiguredSchemaProto;
use Verse\Run\Schema\RunSchemaProto;

class TelegramPullScheme extends PreconfiguredSchemaProto
{

    public function configure()
    {
        $provider = new TelegramGetUpdatesProvider();

        $this->core->addComponent(new UnexpectedShutdownHandler());
        $this->core->addComponent(new CreateDependencyContainer());

        $this->_addCustomComponents();

        $this->core->setProvider($provider);
        $this->core->setProcessor($this->processor ?? new TelegramUpdateProcessor());
        $this->core->setDataChannel(new TelegramReplyChannel());
    }
}