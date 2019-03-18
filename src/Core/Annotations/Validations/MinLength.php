<?php

declare(strict_types=1);

namespace AlexManno\Drunk\Core\Annotations\Validations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Annotation\Target("PROPERTY")
 */
class MinLength implements ValidationAnnotationInterface
{
    /**
     * @var int
     *
     * @Annotation\Required()
     */
    private $min;

    public function __construct(array $config)
    {
        $this->min = (int) $config['min'];
    }

    public function getName(): string
    {
        return 'min';
    }

    /**
     * @return int
     */
    public function getAttributes(): array
    {
        return [
            'min' => $this->min,
        ];
    }
}
