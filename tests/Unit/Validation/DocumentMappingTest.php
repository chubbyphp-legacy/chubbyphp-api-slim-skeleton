<?php

namespace Chubbyphp\Tests\ApiSkeleton\Unit\Validation;

use Chubbyphp\ApiSkeleton\Model\Document;
use Chubbyphp\Validation\Constraint\NotBlankConstraint;
use Chubbyphp\Validation\Constraint\NotNullConstraint;
use Chubbyphp\Validation\Mapping\PropertyMapping;
use PHPUnit\Framework\TestCase;
use Chubbyphp\ApiSkeleton\Validation\DocumentMapping;

/**
 * @covers \Chubbyphp\ApiSkeleton\Validation\DocumentMapping
 */
class DocumentMappingTest extends TestCase
{
    public function testGetClass()
    {
        $mapping = new DocumentMapping();

        self::assertSame(Document::class, $mapping->getClass());
    }

    public function testGetConstraints()
    {
        $mapping = new DocumentMapping();

        self::assertEquals([], $mapping->getConstraints());
    }

    public function testGetPropertyMappings()
    {
        $mapping = new DocumentMapping();

        self::assertEquals([
            new PropertyMapping('name', [new NotNullConstraint(), new NotBlankConstraint()]),
            new PropertyMapping('url', [new NotNullConstraint(), new NotBlankConstraint()]),
        ], $mapping->getPropertyMappings());
    }
}
