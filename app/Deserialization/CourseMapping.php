<?php

declare(strict_types=1);

namespace Chubbyphp\ApiSkeleton\Deserialization;

use Chubbyphp\ApiSkeleton\Model\Document;
use Chubbyphp\Deserialization\Mapping\ObjectMappingInterface;
use Chubbyphp\Deserialization\Mapping\PropertyMapping;
use Chubbyphp\Deserialization\Mapping\PropertyMappingInterface;
use Chubbyphp\ApiSkeleton\Model\Course;
use Chubbyphp\DeserializationModel\Deserializer\PropertyModelCollectionDeserializer;

final class CourseMapping implements ObjectMappingInterface
{
    /**
     * @return string
     */
    public function getClass(): string
    {
        return Course::class;
    }

    /**
     * @return callable
     */
    public function getFactory(): callable
    {
        return [Course::class, 'create'];
    }

    /**
     * @return PropertyMappingInterface[]
     */
    public function getPropertyMappings(): array
    {
        return [
            new PropertyMapping('name'),
            new PropertyMapping('level'),
            new PropertyMapping('progress'),
            new PropertyMapping('active'),
            new PropertyMapping('documents', new PropertyModelCollectionDeserializer(Document::class, true)),
        ];
    }
}
