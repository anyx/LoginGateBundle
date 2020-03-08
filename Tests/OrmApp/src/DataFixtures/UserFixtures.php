<?php

namespace OrmApp\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use OrmApp\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends AbstractFixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

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

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function getPasswordEncoder(): UserPasswordEncoderInterface
    {
        return $this->passwordEncoder;
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
