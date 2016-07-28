<?php

namespace Anyx\LoginGateBundle\Model;

abstract class FailureLoginAttempt
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var int
     */
    protected $ip;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var array
     */
    protected $data;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIp()
    {
        return long2ip($this->ip);
    }

    public function setIp($ip)
    {
        if (!is_int($ip)) {
            $ip = ip2long($ip);
        }

        $this->ip = $ip;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }
}
