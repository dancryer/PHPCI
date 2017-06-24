<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;

interface ProjectGroupInterface
{
    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @return UuidInterface
     */
    public function getIdentifier(): UuidInterface;

    /**
     * @return Collection|ProjectInterface[]
     */
    public function getProjects(): Collection;
}
