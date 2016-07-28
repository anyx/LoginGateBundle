<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__ . '/../../../vendor/autoload.php';

require __DIR__ . '/MysqlAppKernel.php';

$loader->addPsr4('MysqlAppBundle\\', __DIR__ . '/../src');

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

return $loader;
