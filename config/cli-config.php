<?php

use Doctrine\ORM\Tools\Console\ConsoleRunner;

// replace with file to your own project bootstrap
require_once __DIR__ . '/../vendor/autoload.php';

// replace with mechanism to retrieve EntityManager in your app
$envs = \Dotenv\Dotenv::create(__DIR__ . '/../');
$envs->load();

$doctrine = new \AlexManno\Drunk\Core\Doctrine();

$entityManager = $doctrine->setUp();

return ConsoleRunner::createHelperSet($entityManager);
