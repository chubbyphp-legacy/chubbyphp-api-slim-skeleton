<?php

declare(strict_types=1);

namespace Chubbyphp\ApiSkeleton;

use Chubbyphp\ApiSkeleton\Model\Document;
use Chubbyphp\ApiSkeleton\Validation\DocumentMapping;
use Chubbyphp\Model\Resolver;
use Chubbyphp\Validation\Mapping\LazyObjectMapping;
use Chubbyphp\Validation\Provider\ValidationProvider;
use Slim\Container;
use Chubbyphp\ApiSkeleton\Model\Course;
use Chubbyphp\ApiSkeleton\Search\CourseSearch;
use Chubbyphp\ApiSkeleton\Validation\CourseMapping;
use Chubbyphp\ApiSkeleton\Validation\CourseSearchMapping;

/* @var Container $container */

$container->register(new ValidationProvider());

$container->extend('validator.objectmappings', function (array $objectMappings) use ($container) {
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

$container[CourseMapping::class] = function () use ($container) {
    return new CourseMapping($container[Resolver::class]);
};

$container[CourseSearchMapping::class] = function () {
    return new CourseSearchMapping();
};

$container[DocumentMapping::class] = function () use ($container) {
    return new DocumentMapping($container[Resolver::class]);
};
