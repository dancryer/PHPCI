<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Kiboko\Bundle\ContinuousIntegrationBundle\Entity\UserInterface;
use Ramsey\Uuid\UuidInterface;

class UserRepository extends EntityRepository implements UserRepositoryInterface
{
    /**
     * @param UuidInterface $identifier
     *
     * @return UserInterface
     */
    public function findOneById(UuidInterface $identifier): UserInterface
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
     * @param string $email
     *
     * @return UserInterface
     */
    public function findOneByEmail(string $email): UserInterface
    {
        return $this->findOneBy(
            [
                'email' => $email
            ]
        );
    }

    /**
     * @param string $username
     *
     * @return UserInterface
     */
    public function findOneByUsername(string $username): UserInterface
    {
        return $this->findOneBy(
            [
                'username' => $username
            ]
        );
    }
}
