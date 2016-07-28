<?php

namespace MongoDBAppBundle\Service;

use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    /**
     * @var \Doctrine\ODM\MongoDB\DocumentManager
     */
    private $documentManager;

    /**
     * @var string
     */
    private $documentClass;

    /**
     * @param \Doctrine\ODM\MongoDB\DocumentManager $documentManager
     * @param string $documentClass
     */
    public function __construct(DocumentManager $documentManager, $documentClass)
    {
        $this->documentManager = $documentManager;
        $this->documentClass = $documentClass;
    }

    /**
     * @param string $username
     * @return \Symfony\Component\Security\Core\User\UserProviderInterface
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function loadUserByUsername($username)
    {
        $user = $this->getDocumentManager()->getRepository($this->documentClass)->createQueryBuilder()
            ->field('email')->equals(strtolower($username))
            ->getQuery()
            ->getSingleResult();

        if (!$user) {
            throw new UsernameNotFoundException('User not found.');
        }

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return is_subclass_of($class, $this->documentClass);
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    public function getDocumentManager()
    {
        return $this->documentManager;
    }
}
