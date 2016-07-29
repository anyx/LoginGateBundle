<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__ . '/../../../vendor/autoload.php';

require __DIR__ . '/MongoDBAppKernel.php';

$loader->addPsr4('MongoDBAppBundle\\', __DIR__ . '/../src');

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
AnnotationDriver::registerAnnotationClasses();

return $loader;
