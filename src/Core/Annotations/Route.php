<?php

declare(strict_types=1);

namespace AlexManno\Drunk\Core\Annotations;

use Doctrine\Common\Annotations\Annotation\Required;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target("METHOD")
 */
class Route
{
    /**
     * @var string
     */
    private $method;

    /**
     * @Required()
     *
     * @var string
     */
    private $route;

    /**
     * Route constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->method = $config['method'] ?? 'GET';
        $this->route = $config['route'];
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }
}
