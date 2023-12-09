<?php

namespace Anyx\LoginGateBundle\Entity;

use Anyx\LoginGateBundle\Model\FailureLoginAttemptTrait;
use Doctrine\ORM\Mapping as ORM;
use Anyx\LoginGateBundle\Model\FailureLoginAttemptInterface;

#[ORM\Entity(repositoryClass: FailureLoginAttemptRepository::class)]
#[ORM\Table(name: 'failure_login_attempt')]
class FailureLoginAttempt implements FailureLoginAttemptInterface
{
    use FailureLoginAttemptTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    protected ?string $id;

    #[ORM\Column(type: 'string', length: 45)]
    protected ?string $ip;

    #[ORM\Column(type: 'datetime')]
    protected \DateTimeInterface $createdAt;

    #[ORM\Column(type: 'string', nullable: true)]
    protected ?string $username = null;

    #[ORM\Column(type: 'json', nullable: true)]
    protected array $data = [];
}
