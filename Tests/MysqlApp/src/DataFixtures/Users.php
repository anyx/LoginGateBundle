<?php

namespace MysqlAppBundle\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use MysqlAppBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Users extends AbstractFixture implements ContainerAwareInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function getContainer()
    {
        return $this->container;
    }

    public function load(ObjectManager $manager)
    {
        $peter = new User('peter@mail.com', 'test');

        $encoder = $this->container->get('security.encoder_factory')->getEncoder($peter);
        $peter->setPassword($encoder->encodePassword($peter->getPlainPassword(), $peter->getSalt()));

        $manager->persist($peter);
        $manager->flush();

        $this->setReference('user.peter', $peter);
    }
}
