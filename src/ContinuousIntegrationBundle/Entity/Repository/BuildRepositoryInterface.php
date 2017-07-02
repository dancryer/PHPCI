<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Entity\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Kiboko\Bundle\ContinuousIntegrationBundle\Entity\BuildInterface;
use Kiboko\Bundle\ContinuousIntegrationBundle\Entity\ProjectInterface;
use Ramsey\Uuid\UuidInterface;

interface BuildRepositoryInterface extends ObjectRepository
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
     * @param string $commit
     *
     * @return BuildInterface
     */
    public function findOneByCommit(string $commit): BuildInterface;

    /**
     * @param ProjectInterface $project
     *
     * @return BuildInterface[]
     */
    public function findByProject(ProjectInterface $project): array;

    /**
     * @param int $status
     *
     * @return BuildInterface[]
     */
    public function findByStatus(int $status): array;
}
