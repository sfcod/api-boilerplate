<?php

namespace App\EventListener;

use Doctrine\DBAL\Schema\PostgreSqlSchemaManager;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;

/**
 * Class FixPostgreSQLDefaultSchemaListener
 *
 * Fix for pgsql "CREATE SCHEMA public" on migration diff
 * See: https://github.com/doctrine/dbal/issues/1110
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package App\EventListener
 */
final class FixPostgreSQLDefaultSchemaListener
{
    /**
     * @throws \Doctrine\DBAL\Schema\SchemaException
     */
    public function postGenerateSchema(GenerateSchemaEventArgs $args): void
    {
        $schemaManager = $args
            ->getEntityManager()
            ->getConnection()
            ->getSchemaManager();

        if (!$schemaManager instanceof PostgreSqlSchemaManager) {
            return;
        }

        foreach ($schemaManager->getExistingSchemaSearchPaths() as $namespace) {
            if (!$args->getSchema()->hasNamespace($namespace)) {
                $args->getSchema()->createNamespace($namespace);
            }
        }
    }
}
