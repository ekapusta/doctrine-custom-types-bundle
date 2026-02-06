<?php

require_once __DIR__.'/../vendor/autoload.php';

if (class_exists(Doctrine\DBAL\Platforms\MySQLPlatform::class) && !class_exists(Doctrine\DBAL\Platforms\MySqlPlatform::class)) {
    class_alias(Doctrine\DBAL\Platforms\MySQLPlatform::class, Doctrine\DBAL\Platforms\MySqlPlatform::class);
}

if (class_exists(Doctrine\DBAL\Platforms\PostgreSQLPlatform::class) && !class_exists(Doctrine\DBAL\Platforms\PostgreSQL92Platform::class)) {
    class_alias(Doctrine\DBAL\Platforms\PostgreSQLPlatform::class, Doctrine\DBAL\Platforms\PostgreSQL92Platform::class);
}
if (class_exists(Doctrine\DBAL\Platforms\PostgreSqlPlatform::class) && !class_exists(Doctrine\DBAL\Platforms\PostgreSQL92Platform::class)) {
    class_alias(Doctrine\DBAL\Platforms\PostgreSqlPlatform::class, Doctrine\DBAL\Platforms\PostgreSQL92Platform::class);
}

if (!class_exists(Doctrine\DBAL\Driver\PDOMySql\Driver::class)) {
    class_alias(Doctrine\DBAL\Driver\PDO\MySQL\Driver::class, Doctrine\DBAL\Driver\PDOMySql\Driver::class);
}

if (!class_exists(Symfony\Component\DependencyInjection\DefinitionDecorator::class)) {
    if (class_exists(Symfony\Component\DependencyInjection\ChildDefinition::class)) {
        class_alias(Symfony\Component\DependencyInjection\ChildDefinition::class, Symfony\Component\DependencyInjection\DefinitionDecorator::class);
    }
}
