<?php

return [
    'settings' => [
        'displayErrorDetails' => true,
        'routerCacheFile' => false,
    ],
    'projectSettings' => [
        'debug' => true,
        'monolog.level' => 'debug',
        'swiftmailer.options' => [
            'host' => 'localhost',
            'port' => '25',
            'username' => null,
            'password' => null,
            'encryption' => null,
            'auth_mode' => null
        ],
    ],
];
