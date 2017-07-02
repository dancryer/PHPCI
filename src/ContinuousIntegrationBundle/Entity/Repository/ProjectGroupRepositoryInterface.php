<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Entity\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Kiboko\Bundle\ContinuousIntegrationBundle\Entity\ProjectGroupInterface;
use Ramsey\Uuid\UuidInterface;

interface ProjectGroupRepositoryInterface extends ObjectRepository
{
    /**
     * @param UuidInterface $identifier
     *
     * @return ProjectGroupInterface
     */
    public function findOneById(UuidInterface $identifier): ProjectGroupInterface;

    /**
     * @param UuidInterface $identifier
     *
     * @return ProjectGroupInterface
     */
    public function findOneByIdentifier(UuidInterface $identifier): ProjectGroupInterface;
}
