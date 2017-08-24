<?php

declare(strict_types=1);

namespace Chubbyphp\ApiSkeleton;

use Slim\Collection;
use Slim\Container;

$container = new Container();

$rootDir = __DIR__.'/..';

$container['cacheDir'] = $rootDir.'/var/cache/'.$env;
$container['configDir'] = $rootDir.'/config';
$container['logDir'] = $rootDir.'/var/log/'.$env;
$container['publicDir'] = $rootDir.'/public';
$container['rootDir'] = $rootDir;
$container['translationDir'] = $rootDir.'/translations';
$container['vendorDir'] = $rootDir.'/vendor';

if (!is_dir($container['cacheDir'])) {
    mkdir($container['cacheDir'], 0777, true);
}

if (!is_dir($container['logDir'])) {
    mkdir($container['logDir'], 0777, true);
}

$config = array_replace_recursive(
    require $container['configDir'].'/config.php',
    require $container['configDir'].'/config_'.$env.'.php'
);

// slim settings
$container->extend('settings', function (Collection $settings) use ($config) {
    $settings->replace($config['settings']);

    return $settings;
});

require_once __DIR__.'/services.php';

// project settings
foreach ($config['projectSettings'] as $key => $value) {
    $container[$key] = $value;
}

return $container;
