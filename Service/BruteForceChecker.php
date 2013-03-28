<?php

namespace Anyx\LoginGateBundle\Service;

use Symfony\Component\HttpFoundation\Request;

/**
 * 
 */
class BruteForceChecker
{
    const COUNT_LOGIN_ERRORS = '_security.count_errors';
    
    /**
     *
     * @var \SevenDays\BigBroBundle\Service\Logger
     */
    private $logger;
    
    /**
     * @var array
     */
    private $options = array(
        'count_allow_login_attempts' => 3
    );

    /**
     * 
     * @param \SevenDays\BigBroBundle\Service\Logger $logger
     */
    public function __construct(Logger $logger, array $options)
    {
        $this->logger = $logger;
        $this->options = $options;
    }

    public function check(Request $request)
    {
        $session = $request->getSession();
    }
}
