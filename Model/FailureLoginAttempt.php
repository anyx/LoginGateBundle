<?php

namespace Anyx\LoginGateBundle\Model;

abstract class FailureLoginAttempt
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $ip;

    /**
     * @var \DateTimeInterface
     */
    protected $createdAt;

    /**
     * @var string|null
     */
    protected $username;

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

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip)
    {
        $this->ip = $ip;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt)
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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username)
    {
        $this->username = $username;
    }
}
