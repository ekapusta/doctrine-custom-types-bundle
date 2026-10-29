# Doctrine custom types bundle

[![Test](https://github.com/ekapusta/doctrine-custom-types-bundle/actions/workflows/test.yaml/badge.svg)](https://github.com/ekapusta/doctrine-custom-types-bundle/actions/workflows/test.yaml)

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

## UUID types

Both keep the value in PHP as RFC 4122 string, values read from database are lower case.
Pick the type by column: the type can't see the column, and without strict `sql_mode`
MariaDB stores a value of the other format as `NULL` or truncated.

`native_uuid` is for MariaDB's native `UUID` column (10.7+), MySQL has no such type.
Database `uuid` columns map to it on `pdo_mysql`/`mysqli` drivers.

    /**
     * @ORM\Column(name="external_id", type="native_uuid", nullable=true)
     */
    private $externalId;

`binary_uuid` is for `BINARY(16)` column. Plain binary columns are not mapped to it, so set
the type explicitly. Typed parameter is required in DQL and query builder, otherwise the
string doesn't match any row:

    /**
     * @ORM\Column(name="request_id", type="binary_uuid")
     */
    private $requestId;

    $qb->andWhere('e.requestId = :id')->setParameter('id', $id, BinaryUuidType::TYPE_NAME);

`BINARY(16)` column can be converted to `UUID`, values are kept. Changing column type
rebuilds the table, plan it for big tables:

    ALTER TABLE some_table MODIFY request_id UUID NOT NULL;


## PostgreSQL CUBE type

Supports both points and point's sets. Also all cube's functions added.
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
