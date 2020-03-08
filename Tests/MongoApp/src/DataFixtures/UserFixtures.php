<?php

namespace MongoApp\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use MongoApp\Document\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends AbstractFixture
{
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

    public function getPasswordEncoder(): UserPasswordEncoderInterface
    {
        return $this->container->get(UserPasswordEncoderInterface::class);
    }

    public function getReferenceCode(): string
    {
        return 'user';
    }

    public function createModel(array $datum, string $code, ObjectManager $manager)
    {
        $user = new User($datum['email']);

        $user->setPassword($this->getPasswordEncoder()->encodePassword($user, $datum['password']));

        return $user;
    }
}
