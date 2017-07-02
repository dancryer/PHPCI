<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="kiboko_project_group",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(columns={"identifier"})
 *     }
 * )
 */
class ProjectGroup implements ProjectGroupInterface
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
     * @ORM\ManyToOne(targetEntity="Project", cascade={"all"}, fetch="EAGER")
     *
     * @var Collection|ProjectInterface[]
     */
    private $projects;

    /**
     * Project constructor.
     */
    public function __construct()
    {
        $this->projects = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return ProjectGroupInterface
     */
    public function setId(int $id): ProjectGroupInterface
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
     * @param UuidInterface $identifier
     *
     * @return ProjectGroupInterface
     */
    public function setIdentifier(UuidInterface $identifier): ProjectGroupInterface
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @return Collection|ProjectInterface[]
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    /**
     * @param ProjectInterface $project
     *
     * @return ProjectGroupInterface
     */
    public function addBuild(ProjectInterface $project): ProjectGroupInterface
    {
        $this->projects->add($project);

        return $this;
    }

    /**
     * @param Collection|ProjectInterface[] $projects
     *
     * @return ProjectGroupInterface
     */
    public function setBuilds(Collection $projects): ProjectGroupInterface
    {
        $this->projects = $projects;

        return $this;
    }
}
