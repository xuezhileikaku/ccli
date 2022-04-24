<?php
if (php_sapi_name() !== 'cli') {
    exit();
}
require __DIR__ . '/vendor/autoload.php';


use Ccli\App;
use Ccli\CommandCall;

$app = new App();
$app->setSignature("Ccli hello name [ user=name ]");

$app->registerCommand('help', function (CommandCall $call) use ($app) {
    $app->printSignature();
    print_r($call->params);
});


$app->runCommand($argv);
