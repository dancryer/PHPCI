<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Entity;

use Ramsey\Uuid\UuidInterface;

interface BuildInterface
{
    /**
     * @return mixed
     */
    public function getId(): int;

    /**
     * @return UuidInterface
     */
    public function getIdentifier(): UuidInterface;

    /**
     * @return ProjectInterface
     */
    public function getProject(): ProjectInterface;

    /**
     * @return string
     */
    public function getCommit(): string;

    /**
     * @return int
     */
    public function getStatus(): int;

    /**
     * @return string
     */
    public function getLog(): string;

    /**
     * @return string
     */
    public function getBranch(): string;

    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface;

    /**
     * @return \DateTimeInterface
     */
    public function getStartedAt(): \DateTimeInterface;

    /**
     * @return \DateTimeInterface
     */
    public function getFinishedAt(): \DateTimeInterface;

    /**
     * @return string
     */
    public function getCommitterEmail(): string;

    /**
     * @return string
     */
    public function getCommitMessage(): string;

    /**
     * @return array
     */
    public function getExtra(): array;
}
