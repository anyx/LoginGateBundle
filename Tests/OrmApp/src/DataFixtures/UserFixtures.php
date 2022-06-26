<?php

namespace OrmApp\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use OrmApp\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends AbstractFixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function getData(): array
    {
        return [
            'peter' => [
                'email' => 'peter@example.com',
                'password' => 'password',
            ],
            'helen' => [
                'email' => 'hk@example.com',
                'password' => 'password',
            ],
        ];
    }

    public function getReferenceCode(): string
    {
        return 'user';
    }

    public function createModel(array $datum, string $code, ObjectManager $manager)
    {
        $user = new User($datum['email']);

        $user->setPassword($this->passwordHasher->hashPassword($user, $datum['password']));

        return $user;
    }
}
