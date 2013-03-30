<?php

namespace Anyx\LoginGateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(indexes={@ORM\Index(name="ip", columns={"ip"})})
 * @ORM\Entity(repositoryClass="\Anyx\LoginGateBundle\Entity\FailureLoginAttemptRepository")
 * @ORM\HasLifecycleCallbacks
 */
class FailureLoginAttempt
{
    /**
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(name="ip", type="integer")
     */
    private $ip;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     * @var \DateTime
     */
    private $createdAt;
    
    /**
     * @ORM\Column(name="data", type="array")
     */
    private $data;
    
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
    
    /**
     * @ORM\PrePersist
     */
    public function onPersistSetDate()
    {
        if (empty($this->createdAt)) {
            $this->createdAt = new \DateTime();
        }
    }
}
