<?php

namespace Ekapusta\DoctrineCustomTypesBundle\Tests\ORM\Entities;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

/**
 * @Entity()
 */
class Reference
{
    /**
     * @Id()
     * @Column(type="string")
     * @GeneratedValue()
     */
    public $id;

    /**
     * @Column(type="string")
     */
    public $name;
}
