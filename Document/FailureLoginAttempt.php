<?php

namespace Anyx\LoginGateBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

use Anyx\LoginGateBundle\Model\FailureLoginAttempt as BaseFailureLoginAttempt;

class FailureLoginAttempt extends BaseFailureLoginAttempt
{
}
