<?php

declare(strict_types=1);

namespace AlexManno\Drunk\Commands;

use AlexManno\Drunk\Core\Services\RouteDiscovery;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TrialCommand extends Command
{
    /** @var RouteDiscovery */
    private $discovery;

    /**
     * TrialCommand constructor.
     *
     * @param RouteDiscovery $discovery
     */
    public function __construct(RouteDiscovery $discovery)
    {
        parent::__construct('drunk:trial');

        $this->discovery = $discovery;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @throws \ReflectionException
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        print_r($this->discovery->getRoutes());

        $output->writeln('ciao');
    }
}
