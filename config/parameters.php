<?php

use function DI\env;

return [
    'base_dir' => dirname(__DIR__),
    'env' => env('ENVIRONMENT', 'dev'),
    'db.driver' => env('DATABASE_DRIVER', 'pdo_mysql'),
    'db.host' => env('DATABASE_HOST', 'localhost'),
    'db.user' => env('DATABASE_USERNAME'),
    'db.password' => env('DATABASE_PASSWORD'),
    'db.dbname' => env('DATABASE_DBNAME'),
];