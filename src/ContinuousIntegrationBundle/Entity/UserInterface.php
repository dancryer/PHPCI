<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Entity;

use FOS\UserBundle\Model\UserInterface as FOSUserInterface;
use Ramsey\Uuid\UuidInterface;

interface UserInterface extends FOSUserInterface
{
    /**
     * @return UuidInterface
     */
    public function getIdentifier(): UuidInterface;
}
