<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="kiboko_build",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(columns={"identifier"})
 *     }
 * )
 */
class Build implements BuildInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="uuid_binary")
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     *
     * @var UuidInterface
     */
    private $identifier;

    /**
     * @ORM\OneToMany(targetEntity="Project", mappedBy="user", cascade={"persist", "remove", "merge"}, orphanRemoval=false)
     *
     * @var ProjectInterface
     */
    private $project;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    private $commit;

    /**
     * @var int
     */
    private $status;

    /**
     * @var string
     */
    private $log;

    /**
     * @var string
     */
    private $branch;

    /**
     * @var \DateTimeInterface
     */
    private $createdAt;

    /**
     * @var \DateTimeInterface
     */
    private $startedAt;

    /**
     * @var \DateTimeInterface
     */
    private $finishedAt;

    /**
     * @var string
     */
    private $committerEmail;

    /**
     * @var string
     */
    private $commitMessage;

    /**
     * @var array
     */
    private $extra;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     *
     * @return $this
     */
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return UuidInterface
     */
    public function getIdentifier(): UuidInterface
    {
        return $this->identifier;
    }

    /**
     * @param mixed $identifier
     *
     * @return BuildInterface
     */
    public function setIdentifier(UuidInterface $identifier): BuildInterface
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @return ProjectInterface
     */
    public function getProject(): ProjectInterface
    {
        return $this->project;
    }

    /**
     * @param Project $project
     *
     * @return BuildInterface
     */
    public function setProject(Project $project): BuildInterface
    {
        $this->project = $project;

        return $this;
    }

    /**
     * @return string
     */
    public function getCommit(): string
    {
        return $this->commit;
    }

    /**
     * @param mixed $commit
     *
     * @return BuildInterface
     */
    public function setCommit($commit): BuildInterface
    {
        $this->commit = $commit;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     *
     * @return BuildInterface
     */
    public function setStatus(int $status): BuildInterface
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getLog(): string
    {
        return $this->log;
    }

    /**
     * @param string $log
     *
     * @return BuildInterface
     */
    public function setLog(string $log): BuildInterface
    {
        $this->log = $log;

        return $this;
    }

    /**
     * @return string
     */
    public function getBranch(): string
    {
        return $this->branch;
    }

    /**
     * @param string $branch
     *
     * @return BuildInterface
     */
    public function setBranch(string $branch): BuildInterface
    {
        $this->branch = $branch;

        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeInterface $createdAt
     *
     * @return BuildInterface
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): BuildInterface
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getStartedAt(): \DateTimeInterface
    {
        return $this->startedAt;
    }

    /**
     * @param \DateTimeInterface $startedAt
     *
     * @return BuildInterface
     */
    public function setStartedAt(\DateTimeInterface $startedAt): BuildInterface
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getFinishedAt(): \DateTimeInterface
    {
        return $this->finishedAt;
    }

    /**
     * @param \DateTimeInterface $finishedAt
     *
     * @return BuildInterface
     */
    public function setFinishedAt(\DateTimeInterface $finishedAt): BuildInterface
    {
        $this->finishedAt = $finishedAt;

        return $this;
    }

    /**
     * @return string
     */
    public function getCommitterEmail(): string
    {
        return $this->committerEmail;
    }

    /**
     * @param string $committerEmail
     *
     * @return BuildInterface
     */
    public function setCommitterEmail(string $committerEmail): BuildInterface
    {
        $this->committerEmail = $committerEmail;

        return $this;
    }

    /**
     * @return string
     */
    public function getCommitMessage(): string
    {
        return $this->commitMessage;
    }

    /**
     * @param string $commitMessage
     *
     * @return BuildInterface
     */
    public function setCommitMessage(string $commitMessage): BuildInterface
    {
        $this->commitMessage = $commitMessage;

        return $this;
    }

    /**
     * @return array
     */
    public function getExtra(): array
    {
        return $this->extra;
    }

    /**
     * @param array $extra
     *
     * @return BuildInterface
     */
    public function setExtra(array $extra): BuildInterface
    {
        $this->extra = $extra;

        return $this;
    }
}
