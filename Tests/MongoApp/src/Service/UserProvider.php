<?php

namespace MongoApp\Service;

use Doctrine\ODM\MongoDB\DocumentManager;
use MongoApp\Document\User;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    /**
     * @var \Doctrine\ODM\MongoDB\DocumentManager
     */
    protected $documentManager;

    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    /**
     * @param string $username
     *
     * @return User
     *
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function loadUserByUsername($username)
    {
        $qb = $this->getDocumentManager()->getRepository($this->getRepositoryName())->createQueryBuilder();
        $user = $qb
            ->findAndUpdate()
            ->returnNew()
            ->field('email')->equals(strtolower($username))
            ->field('lastLogin')->set(new \DateTime())
            ->getQuery()
            ->execute();

        if (!$user) {
            throw new UsernameNotFoundException('User not found.');
        }

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        $this->getDocumentManager()->refresh($user);
    }

    public function supportsClass($class)
    {
        return is_subclass_of($class, 'MongoApp\\Model\\User');
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    public function getDocumentManager()
    {
        return $this->documentManager;
    }

    protected function getRepositoryName(): string
    {
        return 'Document:User';
    }
}
