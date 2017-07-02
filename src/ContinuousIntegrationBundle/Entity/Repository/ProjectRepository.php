<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Kiboko\Bundle\ContinuousIntegrationBundle\Entity\BuildInterface;
use Ramsey\Uuid\UuidInterface;

class ProjectRepository extends EntityRepository implements ProjectRepositoryInterface
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
}
