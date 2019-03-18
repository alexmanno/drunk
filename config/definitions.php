<?php

use AlexManno\Drunk\Core\Services\CommandsDiscovery;
use AlexManno\Drunk\Core\Services\RouteDiscovery;
use AlexManno\Drunk\Core\Services\RoutesProvider;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\Command\ClearCache\MetadataCommand;
use Doctrine\ORM\Tools\Console\Command\ClearCache\ResultCommand;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Setup;
use FastRoute\Dispatcher;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\HelperSet;
use Zend\HttpHandlerRunner\Emitter\EmitterInterface;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;
use function DI\create;

return [
    EmitterInterface::class => create(SapiEmitter::class),

    Dispatcher::class => function (ContainerInterface $container): Dispatcher {
        return \FastRoute\cachedDispatcher($container->get(RoutesProvider::class), [
            'cacheFile' => $container->get('base_dir') . '/var/cache/routes.cache',
            'disableCache' => $container->get('env'),
        ]);
    },
    Application::class => function (ContainerInterface $container): Application {
        $application = new Application(
            'Drunk',
            '1.0'
        );
        $application->setHelperSet($container->get(HelperSet::class));
        $application->addCommands($container->get('commands'));

        return $application;
    },
    RouteDiscovery::class => function (ContainerInterface $container): RouteDiscovery {
        return new RouteDiscovery(
            'AlexManno\\Drunk\\Controllers',
            sprintf('%s/src/Controllers', $container->get('base_dir')),
            $container->get(AnnotationReader::class)
        );
    },
    CommandsDiscovery::class => function (ContainerInterface $container): CommandsDiscovery {
        return new CommandsDiscovery(
            'AlexManno\\Drunk\\Commands',
            sprintf('%s/src/Commands', $container->get('base_dir'))
        );
    },
    EntityManagerInterface::class => function (ContainerInterface $container): EntityManagerInterface {
        return EntityManager::create(
            [
                'driver' => $container->get('db.driver'),
                'host' => $container->get('db.host'),
                'user' => $container->get('db.user'),
                'password' => $container->get('db.password'),
                'dbname' => $container->get('db.dbname'),
            ],
            $container->get(Configuration::class)
        );
    },
    Filesystem::class => function (ContainerInterface $container): Filesystem {
        return new Filesystem(
            new Local(
                $container->get('base_dir')
            )
        );
    },
    HelperSet::class => function (ContainerInterface $container): HelperSet {
        return ConsoleRunner::createHelperSet($container->get(EntityManagerInterface::class));
    },
    Configuration::class => function (ContainerInterface $container): Configuration {
        return Setup::createAnnotationMetadataConfiguration(
            [sprintf('%s/src', $container->get('base_dir'))],
            'prod' !== $container->get('env'),
            null,
            null,
            false
        );
    },
    'vendor_commands' => [
        MetadataCommand::class,
        ResultCommand::class,
        CreateCommand::class,
        UpdateCommand::class,
        DropCommand::class,
    ],
    'commands' => function (ContainerInterface $container): array {
        return array_map(
            function (string $command) use ($container): Command {
                return $container->get($command);
            },
            array_merge(
                $container->get(CommandsDiscovery::class)->getCommands(),
                $container->get('vendor_commands')
            )
        );
    },
];