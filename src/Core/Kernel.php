<?php

declare(strict_types=1);

namespace AlexManno\Drunk\Core;

use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Psr\Container\ContainerInterface;
use Symfony\Component\Finder\Finder;

class Kernel
{
    /** @var ContainerInterface */
    private $container;

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @return Kernel
     */
    public static function create(): self
    {
        return new self();
    }

    /**
     * @throws \Exception
     *
     * @return Kernel
     */
    public function boot(): self
    {
        $baseDir = dirname(__DIR__, 2);

        $envs = Dotenv::create($baseDir);
        $envs->load();

        $finder = new Finder();
        $finder->files()->in(sprintf('%s/config', $baseDir));

        $containerBuilder = new ContainerBuilder();

        /** @var \SplFileInfo $file */
        foreach ($finder as $file) {
            $definitions = require $file->getPathname();
            if (is_array($definitions)) {
                $containerBuilder->addDefinitions($definitions);
            }
        }

        $this->container = $containerBuilder->build();

        $this->container->set(Kernel::class, $this);

        return $this;
    }
}
