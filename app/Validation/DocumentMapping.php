<?php

declare(strict_types=1);

namespace Chubbyphp\ApiSkeleton\Validation;

use Chubbyphp\Validation\Constraint\ConstraintInterface;
use Chubbyphp\Validation\Constraint\NotBlankConstraint;
use Chubbyphp\Validation\Constraint\NotNullConstraint;
use Chubbyphp\Validation\Mapping\ObjectMappingInterface;
use Chubbyphp\Validation\Mapping\PropertyMapping;
use Chubbyphp\Validation\Mapping\PropertyMappingInterface;
use Chubbyphp\ApiSkeleton\Model\Document;

final class DocumentMapping implements ObjectMappingInterface
{
    /**
     * @return string
     */
    public function getClass(): string
    {
        return Document::class;
    }

    /**
     * @return ConstraintInterface[]
     */
    public function getConstraints(): array
    {
        return [];
    }

    /**
     * @return PropertyMappingInterface[]
     */
    public function getPropertyMappings(): array
    {
        return [
            new PropertyMapping('name', [new NotNullConstraint(), new NotBlankConstraint()]),
            new PropertyMapping('url', [new NotNullConstraint(), new NotBlankConstraint()]),
        ];
    }
}
