<?php

declare(strict_types=1);

if (php_sapi_name() !== 'cli-server') {
    header('HTTP/1.0 403 Forbidden');
    exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}

$loader = require_once __DIR__.'/../app/autoload.php';

$env = 'test';

/** @var \Slim\App $app */
$app = require_once __DIR__ . '/../app/app.php';

$app->run();
