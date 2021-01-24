<?php

chdir(__DIR__);
require_once "vendor/autoload.php";

use Base\Run\Component\BootstrapWorkerDC;
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
$context->fill([
    RunContext::QUEUE_INCOMING => 'http-worker',
    RunContext::HOST     => "Worker",
    RunContext::IDENTITY => ('worker.'.getmypid() . '@' . gethostname()),
    RunContext::IS_SECURE_CONNECTION => false,
    RunContext::GLOBAL_CONFIG => $_ENV
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
$core->run();
