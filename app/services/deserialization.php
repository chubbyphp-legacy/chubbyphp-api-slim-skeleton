<?php

declare(strict_types=1);

namespace Chubbyphp\ApiSkeleton;

use Chubbyphp\ApiSkeleton\Deserialization\DocumentMapping;
use Chubbyphp\ApiSkeleton\Model\Document;
use Chubbyphp\Deserialization\Mapping\LazyObjectMapping;
use Chubbyphp\Deserialization\Provider\DeserializationProvider;
use Slim\Container;
use Chubbyphp\ApiSkeleton\Deserialization\CourseMapping;
use Chubbyphp\ApiSkeleton\Deserialization\CourseSearchMapping;
use Chubbyphp\ApiSkeleton\Model\Course;
use Chubbyphp\ApiSkeleton\Search\CourseSearch;

/* @var Container $container */

$container->register(new DeserializationProvider());

$container->extend('deserializer.objectmappings', function (array $objectMappings) use ($container) {
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

$container[CourseMapping::class] = function () {
    return new CourseMapping();
};

$container[CourseSearchMapping::class] = function () {
    return new CourseSearchMapping();
};

$container[DocumentMapping::class] = function () {
    return new DocumentMapping();
};
