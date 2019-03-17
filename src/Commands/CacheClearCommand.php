<?php

declare(strict_types=1);

namespace AlexManno\Drunk\Commands;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class CacheClearCommand extends Command
{
    /** @var ContainerInterface */
    private $container;

    /**
     * CacheClearCommand constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct('cache:clear');

        $this->container = $container;
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $baseDir = $this->container->get('base_dir');

        $process = new Process(['rm', '-rf', $baseDir.'/var/cache/*']);
    }

}