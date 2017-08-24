<?php

declare(strict_types=1);

namespace Chubbyphp\ApiSkeleton\Deserialization;

use Chubbyphp\Deserialization\Mapping\ObjectMappingInterface;
use Chubbyphp\Deserialization\Mapping\PropertyMapping;
use Chubbyphp\Deserialization\Mapping\PropertyMappingInterface;
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
     * @return callable
     */
    public function getFactory(): callable
    {
        return [CourseSearch::class, 'create'];
    }

    /**
     * @return PropertyMappingInterface[]
     */
    public function getPropertyMappings(): array
    {
        return [
            new PropertyMapping('page'),
            new PropertyMapping('perPage'),
            new PropertyMapping('sort'),
            new PropertyMapping('order'),
        ];
    }
}
