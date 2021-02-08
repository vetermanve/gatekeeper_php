<?php

chdir(__DIR__);
require_once "vendor/autoload.php";

use Base\Run\Component\BootstrapWorkerDC;
use Base\Run\Processor\Modificator\ClearHeadersMessageModificator;
use Monolog\Handler\RotatingFileHandler;
use Verse\Run\RunContext;
use Verse\Run\RunCore;
use Verse\Run\RuntimeLog;
use Verse\Run\Schema\AmqpHttpRequestSchema;

// load env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// start build schema
$schema = new AmqpHttpRequestSchema();
$schema->addComponent(new BootstrapWorkerDC());

$context = new RunContext();
$pidId = ('worker.'.getmypid() . '@' . gethostname());

$context->fill([
    RunContext::HOST     => "Worker",
    RunContext::IDENTITY => $pidId,
    RunContext::IS_SECURE_CONNECTION => false,
    RunContext::GLOBAL_CONFIG => $_ENV + [RunContext::IDENTITY => $pidId],
    RunContext::QUEUE_INCOMING => $_ENV['AMQP_QUEUE'] ?? 'queue-not-defined'
]);

$runtime = new RuntimeLog($context->get(RunContext::IDENTITY));
$runtime->pushHandler(new RotatingFileHandler(__DIR__.'/logs/out.log'));
$runtime->catchErrors();

$core = new RunCore();
$core->setContext($context);
$core->setSchema($schema);
$core->setRuntime($runtime);

$core->configure();
$core->prepare();

// dirty hack
$core->getProcessor()->addMsgModificator(new ClearHeadersMessageModificator());
// end hack

$core->run();
