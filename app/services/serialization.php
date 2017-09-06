<?php

declare(strict_types=1);

namespace Chubbyphp\ApiSkeleton;

use Chubbyphp\ApiHttp\Error\Error;
use Chubbyphp\ApiSkeleton\Model\Document;
use Chubbyphp\ApiSkeleton\Serialization\DocumentMapping;
use Chubbyphp\Serialization\Mapping\LazyObjectMapping;
use Chubbyphp\Serialization\Provider\SerializationProvider;
use Slim\Container;
use Chubbyphp\ApiSkeleton\Model\Course;
use Chubbyphp\ApiSkeleton\Search\Index;
use Chubbyphp\ApiSkeleton\Search\CourseSearch;
use Chubbyphp\ApiSkeleton\Serialization\ErrorMapping;
use Chubbyphp\ApiSkeleton\Serialization\IndexMapping;
use Chubbyphp\ApiSkeleton\Serialization\LinkGenerator;
use Chubbyphp\ApiSkeleton\Serialization\CourseMapping;
use Chubbyphp\ApiSkeleton\Serialization\CourseSearchMapping;

/* @var Container $container */

$container->register(new SerializationProvider());

$container->extend('serializer.objectmappings', function (array $objectMappings) use ($container) {
    $objectMappings[] = new LazyObjectMapping(
        $container,
        ErrorMapping::class,
        Error::class
    );

    $objectMappings[] = new LazyObjectMapping(
        $container,
        IndexMapping::class,
        Index::class
    );

    $objectMappings[] = new LazyObjectMapping(
        $container,
        CourseMapping::class,
        Course::class
    );

    $objectMappings[] = new LazyObjectMapping(
        $container,
        CourseSearchMapping::class,
        CourseSearch::class
    );

    $objectMappings[] = new LazyObjectMapping(
        $container,
        DocumentMapping::class,
        Document::class
    );

    return $objectMappings;
});

$container[LinkGenerator::class] = function () use ($container) {
    return new LinkGenerator($container['router']);
};

$container[ErrorMapping::class] = function () {
    return new ErrorMapping();
};

$container[IndexMapping::class] = function () use ($container) {
    return new IndexMapping($container[LinkGenerator::class]);
};

$container[CourseMapping::class] = function () use ($container) {
    return new CourseMapping($container[LinkGenerator::class]);
};

$container[CourseSearchMapping::class] = function () use ($container) {
    return new CourseSearchMapping($container[LinkGenerator::class]);
};

$container[DocumentMapping::class] = function () {
    return new DocumentMapping();
};
