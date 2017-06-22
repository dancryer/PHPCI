<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use FOS\UserBundle\Model\User as FOSUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="kiboko_user")
 */
class User extends FOSUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    protected $id;
}
