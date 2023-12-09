<?php

namespace Anyx\LoginGateBundle\Model;

interface FailureLoginAttemptInterface
{
    public function getId(): string|int|null;

    public function getIp(): ?string;

    public function setIp(string $ip);

    public function getCreatedAt(): \DateTimeInterface;

    public function getData();

    public function setData(array $data);

    public function getUsername(): ?string;

    public function setUsername(?string $username);
}
