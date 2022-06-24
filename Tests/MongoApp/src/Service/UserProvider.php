<?php

namespace MongoApp\Service;

use Doctrine\ODM\MongoDB\DocumentManager;
use MongoApp\Document\User;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface, PasswordUpgraderInterface
{
    /**
     * @var \Doctrine\ODM\MongoDB\DocumentManager
     */
    protected $documentManager;

    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $qb = $this->getDocumentManager()->getRepository($this->getRepositoryName())->createQueryBuilder();
        $user = $qb
            ->findAndUpdate()
            ->returnNew()
            ->field('email')->equals(strtolower($identifier))
            ->field('lastLogin')->set(new \DateTime())
            ->getQuery()
            ->execute();

        if (!$user) {
            throw new \RuntimeException('User not found.');
        }

        return $user;
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        // TODO: Implement upgradePassword() method.
    }

    public function refreshUser(UserInterface $user)
    {
        return $this->getDocumentManager()->getRepository($this->getRepositoryName())
            ->findOneBy(['email' => $user->getUsername()]);
    }

    public function supportsClass($class)
    {
        $supportedClass = User::class;

        return $class === $supportedClass || is_subclass_of($class, $supportedClass);
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
