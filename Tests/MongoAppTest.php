<?php

namespace Anyx\LoginGateBundle\Tests;

use Doctrine\Common\DataFixtures\Executor\MongoDBExecutor;
use Doctrine\Common\DataFixtures\Purger\MongoDBPurger;
use Symfony\Component\HttpKernel\KernelInterface;

class MongoAppTest extends AbstractLoginGateTestCase
{
    protected static function getKernelClass(): string
    {
        return \MongoApp\Kernel::class;
    }

    protected function loadFixtures(KernelInterface $kernel)
    {
        $dm = $kernel->getContainer()->get('doctrine_mongodb.odm.document_manager');

        $fixturesLoader = static::getContainer()->get('doctrine_mongodb.odm.symfony.fixtures.loader');
        $fixturesLoader->loadFromDirectory($kernel->getProjectDir() . '/src/DataFixtures');

        $fixtures = $fixturesLoader->getFixtures();

        $purger = new MongoDBPurger($dm);
        $executor = new MongoDBExecutor($dm, $purger);

        $executor->execute($fixtures);
        self::$referenceRepository = $executor->getReferenceRepository();
    }
}
