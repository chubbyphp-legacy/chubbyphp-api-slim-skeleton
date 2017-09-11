<?php

$loader = require __DIR__.'/../vendor/autoload.php';
$loader->setPsr4('Chubbyphp\Tests\ApiSkeleton\\', __DIR__);
$loader->setPsr4('Chubbyphp\Tests\Model\Doctrine\DBAL\\', __DIR__.'/../vendor/chubbyphp/chubbyphp-model-doctrine-dbal/tests');

$env = 'test';

/** @var \Slim\Container $container */
$container = require_once __DIR__.'/../app/bootstrap.php';

$removeCacheDirCommand = 'rm -Rf '.$container['cacheDir'];
$removeLogDirCommand = 'rm -Rf '.$container['logDir'];

echo execute($removeCacheDirCommand);
echo execute($removeLogDirCommand);

$dropDatabaseCommand = sprintf(
    'mysql -h%s -P%d -u%s -p%s -e \'DROP DATABASE `%s`;\'',
    $container['db.options']['host'],
    $container['db.options']['port'],
    $container['db.options']['user'],
    $container['db.options']['password'],
    $container['db.options']['dbname']
);

echo execute($dropDatabaseCommand);

$commands = [
    'bin/console chubbyphp:model:dbal:database:create --env=test',
    'bin/console chubbyphp:model:dbal:database:schema:update --dump --force --env=test',
];

foreach ($commands as $command) {
    echo execute('cd '.$container['rootDir'].' && '.$command);
}

echo PHP_EOL;

/**
 * @param string $command
 *
 * @return string
 */
function execute(string $command): string
{
    $output = [
        'command: '.$command,
    ];

    exec($command, $output);

    return implode(PHP_EOL, $output).PHP_EOL.PHP_EOL;
}
