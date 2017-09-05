<?php

declare(strict_types=1);

namespace Chubbyphp\ApiSkeleton;

use Slim\Container;
use Chubbyphp\ApiSkeleton\Controller\IndexController;
use Chubbyphp\ApiSkeleton\Controller\Course\CourseCreateController;
use Chubbyphp\ApiSkeleton\Controller\Course\CourseDeleteController;
use Chubbyphp\ApiSkeleton\Controller\Course\CourseReadController;
use Chubbyphp\ApiSkeleton\Controller\Course\CourseSearchController;
use Chubbyphp\ApiSkeleton\Controller\Course\CourseUpdateController;
use Chubbyphp\ApiSkeleton\Repository\CourseRepository;

/* @var Container $container */

$container[IndexController::class] = function () use ($container) {
    return new IndexController($container['api-http.request.manager'], $container['api-http.response.manager']);
};

$container[CourseSearchController::class] = function () use ($container) {
    return new CourseSearchController(
        $container['defaultLanguage'],
        $container[CourseRepository::class],
        $container['api-http.request.manager'],
        $container['api-http.response.manager'],
        $container['translator'],
        $container['validator']
    );
};

$container[CourseCreateController::class] = function () use ($container) {
    return new CourseCreateController(
        $container['defaultLanguage'],
        $container[CourseRepository::class],
        $container['api-http.request.manager'],
        $container['api-http.response.manager'],
        $container['translator'],
        $container['validator']
    );
};

$container[CourseReadController::class] = function () use ($container) {
    return new CourseReadController(
        $container[CourseRepository::class],
        $container['api-http.request.manager'],
        $container['api-http.response.manager']
    );
};

$container[CourseUpdateController::class] = function () use ($container) {
    return new CourseUpdateController(
        $container['defaultLanguage'],
        $container[CourseRepository::class],
        $container['api-http.request.manager'],
        $container['api-http.response.manager'],
        $container['translator'],
        $container['validator']
    );
};

$container[CourseDeleteController::class] = function () use ($container) {
    return new CourseDeleteController(
        $container[CourseRepository::class],
        $container['api-http.request.manager'],
        $container['api-http.response.manager']
    );
};
