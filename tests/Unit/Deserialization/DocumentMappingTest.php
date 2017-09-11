<?php

namespace Chubbyphp\Tests\ApiSkeleton\Unit\Deserialization;

use Chubbyphp\ApiSkeleton\Model\Document;
use Chubbyphp\Deserialization\Mapping\PropertyMapping;
use PHPUnit\Framework\TestCase;
use Chubbyphp\ApiSkeleton\Deserialization\DocumentMapping;

/**
 * @covers \Chubbyphp\ApiSkeleton\Deserialization\DocumentMapping
 */
class DocumentMappingTest extends TestCase
{
    public function testGetClass()
    {
        $mapping = new DocumentMapping();

        self::assertSame(Document::class, $mapping->getClass());
    }

    public function testGetFactory()
    {
        $mapping = new DocumentMapping();

        self::assertSame([Document::class, 'create'], $mapping->getFactory());
    }

    public function testGetPropertyMappings()
    {
        $mapping = new DocumentMapping();

        self::assertEquals([
            new PropertyMapping('name'),
            new PropertyMapping('url'),
        ], $mapping->getPropertyMappings());
    }
}
