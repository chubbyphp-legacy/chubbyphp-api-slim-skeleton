<?php

namespace Chubbyphp\Tests\ApiSkeleton\Unit\Serialization;

use Chubbyphp\Serialization\Mapping\FieldMapping;
use Chubbyphp\ApiSkeleton\Model\Document;
use Chubbyphp\ApiSkeleton\Serialization\DocumentMapping;

/**
 * @covers \Chubbyphp\ApiSkeleton\Serialization\DocumentMapping
 */
class DocumentMappingTest extends AbstractMappingTest
{
    public function testClass()
    {
        $mapping = new DocumentMapping();

        self::assertSame(Document::class, $mapping->getClass());
    }

    public function testGetType()
    {
        $mapping = new DocumentMapping();

        self::assertSame('document', $mapping->getType());
    }

    public function testGetFieldMappings()
    {
        $mapping = new DocumentMapping();

        self::assertEquals([
            new FieldMapping('id'),
            new FieldMapping('name'),
            new FieldMapping('url'),
        ], $mapping->getFieldMappings());
    }

    public function testGetEmbeddedFieldMappings()
    {
        $mapping = new DocumentMapping();

        self::assertSame([], $mapping->getEmbeddedFieldMappings());
    }

    public function testGetLinkMappings()
    {
        $mapping = new DocumentMapping();

        self::assertSame([], $mapping->getLinkMappings());
    }
}
