<?php


namespace Base\Run\Component;


use Psr\Log\LoggerInterface;
use Verse\Di\Env;
use Verse\Modular\ModularContextProto;
use Verse\Router\Router;
use Verse\Router\RouterConfig;
use Verse\Run\Component\RunComponentProto;
use Verse\Run\RunContext;

class BootstrapWorkerDC extends RunComponentProto
{
    public function run()
    {
        $dc = Env::getContainer();
        $dc->setModule(Router::class, function () use ($dc) {
             /** @var ModularContextProto $config */
             $config = $dc->bootstrap('config');
             $router = new Router($config->get(RunContext::IDENTITY));

             $routerConfig = [
                 RouterConfig::ROUTER_CONNECTION_HOST => $config->get('AMQP_HOST'),
                 RouterConfig::ROUTER_CONNECTION_LOGIN => $config->get('AMQP_USER'),
                 RouterConfig::ROUTER_CONNECTION_PASSWORD => $config->get('AMQP_PASS')
             ];

             $router->init($routerConfig);

             return $router;
        });

        $dc->setModule(LoggerInterface::class, function () use ($dc) {
            return $dc->bootstrap('logger');
        });
    }
}