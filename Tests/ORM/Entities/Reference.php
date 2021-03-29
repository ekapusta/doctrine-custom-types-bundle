<?php

namespace Ekapusta\DoctrineCustomTypesBundle\Tests\ORM\Entities;

/**
 * @Entity
 */
class Reference
{
    /**
     * @Id @Column(type="string") @GeneratedValue
     */
    public $id;

    /**
     * @Column(type="string")
     */
    public $name;
}
