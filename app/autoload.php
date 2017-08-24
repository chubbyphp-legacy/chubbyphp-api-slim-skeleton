<?php

declare(strict_types=1);

if (!$loader = @include __DIR__.'/../vendor/autoload.php') {
    die('curl -s http://getcomposer.org/installer | php; php composer.phar install');
}

return $loader;
