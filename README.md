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
