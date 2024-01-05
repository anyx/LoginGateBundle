<?php

use MongoApp\Kernel;

$runtimePath = realpath(dirname(__DIR__) . '/../../vendor/autoload_runtime.php');

require_once $runtimePath;

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
