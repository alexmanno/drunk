<?php

declare(strict_types=1);

namespace AlexManno\Drunk\Core\Annotations\Validations;

interface ValidationAnnotationInterface
{
    public function getName(): string;

    public function getAttributes(): array;
}
