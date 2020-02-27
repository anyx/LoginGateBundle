<?php

namespace MysqlAppBundle\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use MysqlAppBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class Users extends AbstractFixture implements ContainerAwareInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /** @var EncoderFactoryInterface */
    protected $encoderFactory;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->encoderFactory = $container->get('security.encoder_factory');
    }

    public function setEncoderFactory(EncoderFactoryInterface $encoderFactory) {
        $this->encoderFactory = $encoderFactory;
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
