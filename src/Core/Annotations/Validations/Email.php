<?php

declare(strict_types=1);

namespace AlexManno\Drunk\Core\Annotations\Validations;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Email implements ValidationAnnotationInterface
{
    public function getName(): string
    {
        return 'email';
    }

    public function getAttributes(): array
    {
        return [];
    }
}
