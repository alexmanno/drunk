<?php

declare(strict_types=1);

namespace AlexManno\Drunk\Core;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

class Doctrine
{
    /**
     * @throws \Doctrine\ORM\ORMException
     *
     * @return EntityManager
     */
    public static function setUp(): EntityManager
    {
        $config = Setup::createAnnotationMetadataConfiguration(
            [__DIR__ . '/../../src'],
            $_SERVER['ENVIRONMENT'] ?? 'prod',
            null,
            null,
            false
        );

        $conn = [
            'driver' => 'pdo_mysql',
            'host' => $_SERVER['DATABASE_HOST'] ?? 'localhost',
            'user' => $_SERVER['DATABASE_USERNAME'],
            'password' => $_SERVER['DATABASE_PASSWORD'],
            'dbname' => $_SERVER['DATABASE_DBNAME'],
        ];

        return EntityManager::create($conn, $config);
    }
}
