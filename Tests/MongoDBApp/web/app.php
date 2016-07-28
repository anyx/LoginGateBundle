<?php

use Symfony\Component\HttpFoundation\Request;

/** @var Composer\Autoload\ClassLoader */
$loader = require __DIR__ . '/../app/autoload.php';
include_once __DIR__.'/../app/bootstrap.php.cache';

$kernel = new MongoDBAppKernel('test', false);
$kernel->loadClassCache();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
