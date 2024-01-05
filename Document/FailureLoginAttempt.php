<?php

namespace Anyx\LoginGateBundle\Document;

use Anyx\LoginGateBundle\Model\FailureLoginAttemptInterface;
use Anyx\LoginGateBundle\Model\FailureLoginAttemptTrait;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(repositoryClass: FailureLoginAttemptRepository::class)]
class FailureLoginAttempt implements FailureLoginAttemptInterface
{
    use FailureLoginAttemptTrait;

    #[MongoDB\Id]
    protected ?string $id;

    #[MongoDB\Field(type: "string")]
    protected ?string $ip;

    #[MongoDB\Field(type: "date")]
    protected ?\DateTimeInterface $createdAt;

    #[MongoDB\Field(type: "hash")]
    protected ?array $data = [];

    #[MongoDB\Field(type: "string")]
    protected ?string $username;
}
