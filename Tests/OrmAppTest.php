<?php

namespace Anyx\LoginGateBundle\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\HttpKernel\KernelInterface;

class OrmAppTest extends AbstractLoginGateTestCase
{
    protected static $initialized = false;

    protected static function getKernelClass(): string
    {
        return \OrmApp\Kernel::class;
    }

    protected function loadFixtures(KernelInterface $kernel)
    {
        $em = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $loader = $kernel->getContainer()->get('orm.fixtures.loader');
        $fixtures = $loader->getFixtures();

        if (!$fixtures) {
            throw new \InvalidArgumentException('Could not find any fixtures to load');
        }

        if (!static::$initialized) {
            static::executeCommand('doctrine:database:create');
            static::executeCommand('doctrine:schema:drop', ['--force' => true]);
            static::executeCommand('doctrine:schema:create');

            static::$initialized = true;
        }

        $purger = new ORMPurger($em);
        $executor = new ORMExecutor($em, $purger);

        $executor->execute($fixtures);
        self::$referenceRepository = $executor->getReferenceRepository();
    }
}
