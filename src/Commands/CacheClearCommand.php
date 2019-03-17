<?php

declare(strict_types=1);

namespace AlexManno\Drunk\Commands;

use League\Flysystem\Filesystem;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CacheClearCommand extends Command
{
    /** @var Filesystem */
    private $filesystem;

    /**
     * CacheClearCommand constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct('cache:clear');

        $this->filesystem = $filesystem;
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->filesystem->deleteDir('var/cache');
        $this->filesystem->createDir('var/cache');

        $output->writeln('Cache clean');
    }
}
