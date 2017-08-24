<?php

declare(strict_types=1);

namespace Chubbyphp\ApiSkeleton\Deserialization;

use Chubbyphp\Deserialization\Mapping\ObjectMappingInterface;
use Chubbyphp\Deserialization\Mapping\PropertyMapping;
use Chubbyphp\Deserialization\Mapping\PropertyMappingInterface;
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
     * @return callable
     */
    public function getFactory(): callable
    {
        return [Document::class, 'create'];
    }

    /**
     * @return PropertyMappingInterface[]
     */
    public function getPropertyMappings(): array
    {
        return [
            new PropertyMapping('name'),
            new PropertyMapping('url'),
        ];
    }
}
