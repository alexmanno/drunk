<?php

declare(strict_types=1);

namespace AlexManno\Drunk\Core\Services;

use Symfony\Component\Finder\Finder;

class CommandsDiscovery
{
    /** @var string */
    private $namespace;

    /** @var string */
    private $directory;

    /** @var array */
    private $commands = [];

    /**
     * CommandsDiscovery constructor.
     *
     * @param string $namespace
     * @param string $directory
     * @param string $rootDir
     */
    public function __construct(string $namespace, string $directory)
    {
        $this->namespace = $namespace;
        $this->directory = $directory;
    }

    public function getCommands(): array
    {
        if (empty($this->commands)) {
            $this->discoverCommands();
        }

        return $this->commands;
    }

    private function discoverCommands(): void
    {
        $finder = new Finder();
        $finder->files()->in($this->directory);

        /** @var \SplFileInfo $file */
        foreach ($finder as $file) {
            $this->commands[] = $this->namespace . '\\' . $file->getBasename('.php');
        }
    }
}
