<?php

declare(strict_types=1);

namespace AlexManno\Drunk\Commands;

use AlexManno\Drunk\Core\Models\Route;
use AlexManno\Drunk\Core\Services\RouteDiscovery;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RoutesListCommand extends Command
{
    /** @var RouteDiscovery */
    private $discovery;

    public function __construct(RouteDiscovery $discovery)
    {
        parent::__construct('routes:list');

        $this->discovery = $discovery;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws \ReflectionException
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $routes = $this->discovery->getRoutes();

        $routes = array_map(function (Route $route): array {
            return [
                $route->getRouteMethod(),
                $route->getRoute(),
                sprintf(
                    '%s::%s',
                    $route->getClassName(),
                    $route->getClassMethod()
                ),
            ];
        }, $routes);

        $table = new Table($output);

        $table
            ->setHeaders(['Method', 'Route', 'Handler'])
            ->setRows($routes);

        $table->render();
    }
}
