<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="kiboko_project",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(columns={"identifier"})
 *     }
 * )
 */
class Project implements ProjectInterface
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
     * @ORM\ManyToOne(targetEntity="Build", cascade={"all"}, fetch="EAGER")
     *
     * @var Collection|BuildInterface[]
     */
    private $builds;

    /**
     * Project constructor.
     */
    public function __construct()
    {
        $this->builds = new ArrayCollection();
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
     * @return ProjectInterface
     */
    public function setId(int $id): ProjectInterface
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
     * @return ProjectInterface
     */
    public function setIdentifier(UuidInterface $identifier): ProjectInterface
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @return Collection|Build[]
     */
    public function getBuilds(): Collection
    {
        return $this->builds;
    }

    /**
     * @param Build $build
     *
     * @return ProjectInterface
     */
    public function addBuild(Build $build): ProjectInterface
    {
        $this->builds->add($build);

        return $this;
    }

    /**
     * @param Collection|Build[] $builds
     *
     * @return ProjectInterface
     */
    public function setBuilds(Collection $builds): ProjectInterface
    {
        $this->builds = $builds;

        return $this;
    }
}
