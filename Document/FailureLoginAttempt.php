<?php

namespace Anyx\LoginGateBundle\Document;

use Anyx\LoginGateBundle\Model\FailureLoginAttemptInterface;
use Anyx\LoginGateBundle\Model\FailureLoginAttemptTrait;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(repositoryClass="Anyx\LoginGateBundle\Document\FailureLoginAttemptRepository")
 */
class FailureLoginAttempt implements FailureLoginAttemptInterface
{
    use FailureLoginAttemptTrait;

    /**
     * @MongoDB\Id()
     *
     * @var string
     */
    protected $id;

    /**
     * @MongoDB\Field(type="string")
     *
     * @var string
     */
    protected $ip;

    /**
     * @MongoDB\Field(type="date")
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @MongoDB\Field(type="hash")
     *
     * @var array
     */
    protected $data;

    /**
     * @MongoDB\Field(type="string")
     *
     * @var string
     */
    protected $username;
}
