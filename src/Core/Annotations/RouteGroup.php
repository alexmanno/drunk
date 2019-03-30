<?php

declare(strict_types=1);

namespace AlexManno\Drunk\Core\Annotations;

use Doctrine\Common\Annotations\Annotation\Required;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target("CLASS")
 */
class RouteGroup
{
    /**
     * @Required()
     *
     * @var string
     */
    private $prefix;

    /**
     * RouteGroup constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->prefix = $config['prefix'];
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }
}
