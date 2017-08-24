<?php

declare(strict_types=1);

namespace Chubbyphp\ApiSkeleton;

use Chubbyphp\Lazy\LazyCommand;
use Chubbyphp\Model\Doctrine\DBAL\Command\CreateDatabaseCommand;
use Chubbyphp\Model\Doctrine\DBAL\Command\RunSqlCommand;
use Chubbyphp\Model\Doctrine\DBAL\Command\SchemaUpdateCommand;
use Chubbyphp\ApiSkeleton\Provider\ConsoleProvider;
use Slim\Container;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/* @var Container $container */
$container->register(new ConsoleProvider());

$container[CreateDatabaseCommand::class] = function () use ($container) {
    return new CreateDatabaseCommand($container['db']);
};

$container[RunSqlCommand::class] = function () use ($container) {
    return new RunSqlCommand($container['db']);
};

$container[SchemaUpdateCommand::class] = function () use ($container) {
    return new SchemaUpdateCommand($container['db'], __DIR__.'/schema.php');
};

/* @var Container $container */
$container->extend('console.commands', function (array $commands) use ($container) {
    $commands[] = new LazyCommand(
        $container,
        CreateDatabaseCommand::class,
        'chubbyphp:model:dbal:database:create'
    );

    $commands[] = new LazyCommand(
        $container,
        RunSqlCommand::class,
        'chubbyphp:model:dbal:database:run:sql',
        [
            new InputArgument('sql', InputArgument::REQUIRED, 'The SQL statement to execute.'),
            new InputOption('depth', null, InputOption::VALUE_REQUIRED, 'Dumping depth of result set.', 7),
        ]
    );

    $commands[] = new LazyCommand(
        $container,
        SchemaUpdateCommand::class,
        'chubbyphp:model:dbal:database:schema:update',
        [
            new InputOption('dump', null, InputOption::VALUE_NONE, 'Dumps the generated SQL statements'),
            new InputOption('force', 'f', InputOption::VALUE_NONE, 'Executes the generated SQL statements.'),
        ]
    );

    return $commands;
});
