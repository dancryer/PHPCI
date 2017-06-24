<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Entity\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Kiboko\Bundle\ContinuousIntegrationBundle\Entity\UserInterface;
use Ramsey\Uuid\UuidInterface;

interface UserRepositoryInterface extends ObjectRepository
{
    /**
     * @param UuidInterface $identifier
     *
     * @return UserInterface
     */
    public function findOneById(UuidInterface $identifier): UserInterface;

    /**
     * @param UuidInterface $identifier
     *
     * @return UserInterface
     */
    public function findOneByIdentifier(UuidInterface $identifier): UserInterface;

    /**
     * @param string $email
     *
     * @return UserInterface
     */
    public function findOneByEmail(string $email): UserInterface;

    /**
     * @param string $username
     *
     * @return UserInterface
     */
    public function findOneByUsername(string $username): UserInterface;
}
