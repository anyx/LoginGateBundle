<?php

namespace Anyx\LoginGateBundle\Tests;

use Doctrine\Common\DataFixtures\Executor\MongoDBExecutor;
use Doctrine\Common\DataFixtures\Purger\MongoDBPurger;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;
use Symfony\Component\HttpKernel\KernelInterface;

class MysqlAppTest extends AbstractLoginGateTestCase
{
    protected static function getKernelClass()
    {
        return 'MongoDBAppKernel';
    }

    protected function loadFixtures(KernelInterface $kernel)
    {
        $dm = $kernel->getContainer()->get('doctrine_mongodb.odm.document_manager');

        $loader = new ContainerAwareLoader($kernel->getContainer());
        $loader->loadFromDirectory($kernel->getRootDir() . '/../src/DataFixtures');

        $fixtures = $loader->getFixtures();

        $purger = new MongoDBPurger($dm);
        $executor = new MongoDBExecutor($dm, $purger);

        $executor->execute($fixtures);
        self::$referenceRepository = $executor->getReferenceRepository();
    }
}
