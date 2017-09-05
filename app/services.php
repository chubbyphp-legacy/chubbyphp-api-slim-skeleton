<?php

declare(strict_types=1);

namespace Chubbyphp\ApiSkeleton;

use Chubbyphp\ApiHttp\Provider\ApiHttpProvider;
use Chubbyphp\Translation\LocaleTranslationProvider;
use Chubbyphp\Translation\TranslationProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\SwiftmailerServiceProvider;
use Slim\Container;
use Chubbyphp\ApiSkeleton\ApiHttp\ResponseFactory;

/* @var Container $container */
$container->register(new ApiHttpProvider());
$container->register(new MonologServiceProvider());
$container->register(new TranslationProvider());
$container->register(new SwiftmailerServiceProvider());

// extend providers
$container['api-http.response.factory'] = function () {
    return new ResponseFactory();
};

$container->extend('translator.providers', function (array $providers) use ($container) {
    $providers[] = new LocaleTranslationProvider(
        'de',
        array_replace(
            require $container['vendorDir'].'/chubbyphp/chubbyphp-validation/translations/de.php',
            require $container['vendorDir'].'/chubbyphp/chubbyphp-validation-model/translations/de.php'
        )
    );
    $providers[] = new LocaleTranslationProvider(
        'en',
        array_replace(
            require $container['vendorDir'].'/chubbyphp/chubbyphp-validation/translations/en.php',
            require $container['vendorDir'].'/chubbyphp/chubbyphp-validation-model/translations/en.php'
        )
    );

    return $providers;
});

// controller
require_once __DIR__.'/services/controller.php';

// deserialization
require_once __DIR__.'/services/deserialization.php';

// repository
require_once __DIR__.'/services/repository.php';

// serializer
require_once __DIR__.'/services/serialization.php';

// validation
require_once __DIR__.'/services/validation.php';
