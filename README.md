# Doctrine custom types bundle

[![Build Status](https://travis-ci.org/ekapusta/doctrine-custom-types-bundle.svg?branch=develop)](https://travis-ci.org/ekapusta/doctrine-custom-types-bundle)

Add custom types like MySQL's enum.

## To add new type

1. Add it to `Ekapusta\DoctrineCustomTypesBundle\DBAL\Types`
2. Register it at `Ekapusta\DoctrineCustomTypesBundle\DBAL\TypeRegistry`

## MySQL ENUM type

To use it in annotations, pass `values` through `options`:

    /**
     * @var string
     *
     * @ORM\Column(name="sex", type="enum", options={
     *     "values": {"yes", "no", "maybe"},
     *     "default": "yes"
     * })
     */
    private $sex;


## PostgreSQL CUBE type

Supports both points and point's sets.
See https://www.postgresql.org/docs/current/static/cube.html

    /**
     * @var Value\Point
     *
     * @ORM\Column(name="n_space_point", type="cube", options={
     *     "default": "(1, 2, 3)"
     * })
     */
    private $nSpacePoint;

    /**
     * @var Value\PointSet
     *
     * @ORM\Column(name="n_space_cube", type="cube", options={
     *     "default": "(1, 2), (3, 4)"
     * })
     */
    private $nSpaceCube;
