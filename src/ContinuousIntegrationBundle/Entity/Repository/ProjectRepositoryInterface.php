<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Entity\Repository;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectRepository;
use Kiboko\Bundle\ContinuousIntegrationBundle\Entity\BuildInterface;
use Kiboko\Bundle\ContinuousIntegrationBundle\Entity\ProjectGroupInterface;
use Ramsey\Uuid\UuidInterface;

interface ProjectRepositoryInterface extends ObjectRepository
{
    /**
     * @param UuidInterface $identifier
     *
     * @return BuildInterface
     */
    public function findOneById(UuidInterface $identifier): BuildInterface;

    /**
     * @param UuidInterface $identifier
     *
     * @return BuildInterface
     */
    public function findOneByIdentifier(UuidInterface $identifier): BuildInterface;

    /**
     * @param ProjectGroupInterface $projectGroup
     *
     * @return Collection|BuildInterface[]
     */
    public function findByGroup(ProjectGroupInterface $projectGroup): Collection;
}
