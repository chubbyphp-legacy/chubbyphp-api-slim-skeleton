<?php

declare(strict_types=1);

namespace Chubbyphp\ApiSkeleton\Validation;

use Chubbyphp\Validation\Constraint\ConstraintInterface;
use Chubbyphp\Validation\Mapping\PropertyMappingInterface;
use Chubbyphp\Validation\Mapping\ObjectMappingInterface;
use Chubbyphp\ApiSkeleton\Search\CourseSearch;

final class CourseSearchMapping implements ObjectMappingInterface
{
    /**
     * @return string
     */
    public function getClass(): string
    {
        return CourseSearch::class;
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
        return [];
    }
}
