<?php

namespace Kiboko\Component\ContinuousIntegration\Model;

interface UserInterface
{
    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @return string
     */
    public function getEmail(): string;

    /**
     * @return string
     */
    public function getHash(): string;

    /**
     * @return bool
     */
    public function isAdmin(): bool;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param int $id
     */
    public function setId(int $id): void;

    /**
     * @param string $email
     */
    public function setEmail(string $email): void;

    /**
     * @param string $hash
     */
    public function setHash(string $hash): void;

    /**
     * @param bool $isAdmin
     */
    public function setIsAdmin(bool $isAdmin): void;

    /**
     * @param string $name
     */
    public function setName(string $name): void;
}
