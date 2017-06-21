<?php

namespace Kiboko\Component\ContinuousIntegration\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityRepository;
use Kiboko\Component\ContinuousIntegration\Model\UserInterface;
use Ramsey\Uuid\UuidInterface;

class UserRepository extends EntityRepository implements ObjectRepository
{
    /**
     * @param UuidInterface $identifier
     *
     * @return UserInterface
     */
    public function findOneByIdentifier(UuidInterface $identifier): UserInterface
    {
        return $this->findOneBy(
            [
                'identifier' => $identifier
            ]
        );
    }

    /**
     * @param string $identifier
     *
     * @return UserInterface
     */
    public function findOneByEmail(string $identifier): UserInterface
    {
        return $this->findOneBy(
            [
                'email' => $identifier
            ]
        );
    }

    /**
     * @param string $identifier
     *
     * @return UserInterface
     */
    public function findOneByUsername(string $identifier): UserInterface
    {
        return $this->findOneBy(
            [
                'username' => $identifier
            ]
        );
    }
}
