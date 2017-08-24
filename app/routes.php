<?php

declare(strict_types=1);

namespace Chubbyphp\ApiSkeleton;

use Slim\App;
use Slim\Container;
use Chubbyphp\ApiSkeleton\Controller\IndexController;
use Chubbyphp\ApiSkeleton\Controller\Course\CourseCreateController;
use Chubbyphp\ApiSkeleton\Controller\Course\CourseDeleteController;
use Chubbyphp\ApiSkeleton\Controller\Course\CourseReadController;
use Chubbyphp\ApiSkeleton\Controller\Course\CourseSearchController;
use Chubbyphp\ApiSkeleton\Controller\Course\CourseUpdateController;

/* @var App $app */
/* @var Container $container */

$app->group('/api', function () use ($app, $container) {
    $app->get('', IndexController::class)->setName('index');
    $app->group('/courses', function () use ($app, $container) {
        $app->get('', CourseSearchController::class)->setName('course_search');
        $app->post('', CourseCreateController::class)->setName('course_create');
        $app->get('/{id}', CourseReadController::class)->setName('course_read');
        $app->patch('/{id}', CourseUpdateController::class)->setName('course_update');
        $app->delete('/{id}', CourseDeleteController::class)->setName('course_delete');
    });
});
