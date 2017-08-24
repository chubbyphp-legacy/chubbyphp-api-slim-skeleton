<?php

declare(strict_types=1);

namespace Chubbyphp\ApiSkeleton;

use Chubbyphp\ApiSkeleton\Repository\DocumentRepository;
use Chubbyphp\Model\Resolver;
use Chubbyphp\Model\StorageCache\ArrayStorageCache;
use Silex\Provider\DoctrineServiceProvider;
use Slim\Container;
use Chubbyphp\ApiSkeleton\Repository\CourseRepository;

/* @var Container $container */

$container->register(new DoctrineServiceProvider());

$container[ArrayStorageCache::class] = function () {
    return new ArrayStorageCache();
};

$container[CourseRepository::class] = function () use ($container) {
    return new CourseRepository(
        $container['db'],
        $container[Resolver::class],
        $container[ArrayStorageCache::class],
        $container['logger']
    );
};

$container[DocumentRepository::class] = function () use ($container) {
    return new DocumentRepository(
        $container['db'],
        $container[Resolver::class],
        $container[ArrayStorageCache::class],
        $container['logger']
    );
};

$container[Resolver::class] = function () use ($container) {
    return new Resolver($container, [
        CourseRepository::class,
        DocumentRepository::class,
    ]);
};
