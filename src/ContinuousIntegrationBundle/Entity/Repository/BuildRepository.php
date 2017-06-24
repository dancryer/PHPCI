<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Kiboko\Bundle\ContinuousIntegrationBundle\Entity\BuildInterface;
use Kiboko\Bundle\ContinuousIntegrationBundle\Entity\ProjectInterface;
use Ramsey\Uuid\UuidInterface;

class BuildRepository extends EntityRepository implements BuildRepositoryInterface
{
    /**
     * @param UuidInterface $identifier
     *
     * @return BuildInterface
     */
    public function findOneById(UuidInterface $identifier): BuildInterface
    {
        return $this->findOneBy(
            [
                'id' => $identifier
            ]
        );
    }

    /**
     * @param UuidInterface $identifier
     *
     * @return BuildInterface
     */
    public function findOneByIdentifier(UuidInterface $identifier): BuildInterface
    {
        return $this->findOneBy(
            [
                'identifier' => $identifier
            ]
        );
    }

    /**
     * @param string $commit
     *
     * @return BuildInterface
     */
    public function findOneByCommit(string $commit): BuildInterface
    {
        return $this->findOneBy(
            [
                'commit' => $commit
            ]
        );
    }

    /**
     * @param ProjectInterface $project
     *
     * @return BuildInterface[]
     */
    public function findByProject(ProjectInterface $project): array
    {
        return $this->findBy(
            [
                'project' => $project
            ]
        );
    }

    /**
     * @param int $status
     *
     * @return BuildInterface[]
     */
    public function findByStatus(int $status): array
    {
        return $this->findBy(
            [
                'status' => $status
            ]
        );
    }
}
