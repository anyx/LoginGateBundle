<?php

namespace Anyx\LoginGateBundle\Tests;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader as DataFixturesLoader;
use Symfony\Component\HttpKernel\KernelInterface;

class MysqlAppTest extends AbstractLoginGateTestCase
{
    protected static function getKernelClass()
    {
        return 'MysqlAppKernel';
    }

    protected function loadFixtures(KernelInterface $kernel)
    {
        /** @var $doctrine \Doctrine\Common\Persistence\ManagerRegistry */
        $doctrine = $kernel->getContainer()->get('doctrine');
        $em = $doctrine->getManager();

        $this->createDatabase($em);

        $paths = [];
        foreach ($kernel->getBundles() as $bundle) {
            $paths[] = $bundle->getPath() . '/DataFixtures';
        }

        $loader = new DataFixturesLoader($kernel->getContainer());
        $loader->loadFromDirectory($kernel->getRootDir() . '/../src/DataFixtures');

        $fixtures = $loader->getFixtures();
        if (!$fixtures) {
            throw new \RuntimeException(
                sprintf('Could not find any fixtures to load in: %s', "\n\n- ".implode("\n- ", $paths))
            );
        }
        $purger = new ORMPurger($em);
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_DELETE);
        $executor = new ORMExecutor($em, $purger);
        $executor->execute($fixtures);

        self::$referenceRepository = $executor->getReferenceRepository();
    }

    /**
     * @param \Doctrine\ORM\EntityManager $manager
     */
    private function createDatabase(EntityManager $manager)
    {
        $schemaTool = new SchemaTool($manager);
        $metadata = $manager->getMetadataFactory()->getAllMetadata();

        // Drop and recreate tables for all entities
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
    }
}
