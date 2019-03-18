<?php

declare(strict_types=1);

namespace AlexManno\Drunk\Core\Services;

use AlexManno\Drunk\Core\Annotations\Validations\Email;
use AlexManno\Drunk\Core\Annotations\Validations\MinLength;
use AlexManno\Drunk\Core\Annotations\Validations\Required;
use AlexManno\Drunk\Core\Annotations\Validations\ValidationAnnotationInterface;
use Doctrine\Common\Annotations\AnnotationReader;
use Rakit\Validation\Validation;
use Rakit\Validation\Validator as RakitValidator;

class Validator
{
    private const VALIDATION_ANNOTATIONS = [
        Required::class,
        MinLength::class,
        Email::class,
    ];

    /** @var AnnotationReader */
    private $annotationReader;

    /** @var RakitValidator */
    private $validator;

    /**
     * Validator constructor.
     *
     * @param array $messages
     * @param AnnotationReader $annotationReader
     * @param RakitValidator $validator
     */
    public function __construct(AnnotationReader $annotationReader, RakitValidator $validator)
    {
        $this->annotationReader = $annotationReader;
        $this->validator = $validator;
    }

    /**
     * @param string $entityName
     * @param array $entityData
     *
     * @return Validation
     */
    public function validateEntity(string $entityName, array $entityData): Validation
    {
        return $this->validator->validate(
            $entityData,
            $this->getRulesByEntity($entityName)
        );
    }

    /**
     * @param string $entityName
     *
     * @return array
     */
    private function getRulesByEntity(string $entityName): array
    {
        try {
            $reflectionClass = new \ReflectionClass($entityName);
        } catch (\ReflectionException $exception) {
            return [];
        }

        $rules = [];

        foreach ($reflectionClass->getProperties() as $property) {
            foreach (self::VALIDATION_ANNOTATIONS as $annotationClass) {
                /** @var ValidationAnnotationInterface|null $annotation */
                $annotation = $this->annotationReader->getPropertyAnnotation($property, $annotationClass);

                if (null === $annotation) {
                    continue;
                }

                $rules[] = [
                    'field' => $property->getName(),
                    'validation' => $annotation->getName() . (empty($annotation->getAttributes()) ? '' : ':' . implode(',', $annotation->getAttributes())),
                ];
            }
        }

        return array_reduce($rules, function (array $curry, array $rule) {
            if (isset($curry[$rule['field']])) {
                $curry[$rule['field']] .= '|' . $rule['validation'];
            } else {
                $curry[$rule['field']] = $rule['validation'];
            }

            return $curry;
        }, []);
    }
}
