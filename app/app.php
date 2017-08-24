<?php

declare(strict_types=1);

namespace Chubbyphp\ApiSkeleton;

use Slim\App;

$container = require_once __DIR__.'/bootstrap.php';

$app = new App($container);

require_once __DIR__.'/middlewares.php';
require_once __DIR__.'/routes.php';

return $app;
