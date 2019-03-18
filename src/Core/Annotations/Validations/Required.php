<?php

declare(strict_types=1);

namespace AlexManno\Drunk\Core\Annotations\Validations;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Required implements ValidationAnnotationInterface
{
    public function getName(): string
    {
        return 'required';
    }

    public function getAttributes(): array
    {
        return [];
    }
}
