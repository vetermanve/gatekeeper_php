<?php

require_once __DIR__.'/../vendor/autoload.php';

use Base\Render\RenderSetupComponent;
use Base\Run\RoutingProcessor;
use Verse\Run\RunContext;
use Verse\Run\RunCore;
use Verse\Run\RuntimeLog;
use Verse\Run\Schema\RegularHttpRequestSchema;
use Verse\Run\Util\HttpEnvContext;

$env = new HttpEnvContext();
$env->fill([
    HttpEnvContext::HTTP_COOKIE    => &$_COOKIE,
    HttpEnvContext::HTTP_GET       => &$_GET,
    HttpEnvContext::HTTP_POST      => &$_POST,
    HttpEnvContext::HTTP_POST_BODY => trim(file_get_contents("php://input")),
    HttpEnvContext::HTTP_SERVER    => &$_SERVER,
    HttpEnvContext::HTTP_HEADERS   => getallheaders(),
]);


$schema = new RegularHttpRequestSchema();
$schema->setProcessor(new RoutingProcessor());
$schema->setHttpEnv($env);
$schema->addComponent(new RenderSetupComponent());

$context = new RunContext();
$context->fill([
    RunContext::HOST     => $_SERVER['HTTP_HOST'],
    RunContext::IDENTITY => ('http.'.getmypid() . '@' . gethostname()),
    RunContext::IS_SECURE_CONNECTION => stripos($_SERVER['SERVER_PROTOCOL'],'https') === true
]);


$context->set(RunContext::GLOBAL_CONFIG, []);

$runtime = new RuntimeLog($context->get(RunContext::IDENTITY));
$runtime->pushHandler(new \Monolog\Handler\RotatingFileHandler(dirname(__DIR__).'/logs/out.log'));
$runtime->catchErrors();

$core = new RunCore();
$core->setContext($context);
$core->setSchema($schema);
$core->setRuntime($runtime);

$core->configure();
$core->prepare();
$core->run();