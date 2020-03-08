<?php

namespace Anyx\LoginGateBundle\Document;

use Anyx\LoginGateBundle\Model\FailureLoginAttempt as BaseFailureLoginAttempt;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(repositoryClass="Anyx\LoginGateBundle\Document\FailureLoginAttemptRepository")
 */
class FailureLoginAttempt extends BaseFailureLoginAttempt
{
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
}
