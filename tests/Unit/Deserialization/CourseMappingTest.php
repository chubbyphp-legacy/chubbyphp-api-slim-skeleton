<?php

namespace Chubbyphp\Tests\ApiSkeleton\Unit\Deserialization;

use Chubbyphp\ApiSkeleton\Model\Document;
use Chubbyphp\Deserialization\Mapping\PropertyMapping;
use Chubbyphp\DeserializationModel\Deserializer\PropertyModelCollectionDeserializer;
use PHPUnit\Framework\TestCase;
use Chubbyphp\ApiSkeleton\Deserialization\CourseMapping;
use Chubbyphp\ApiSkeleton\Model\Course;

/**
 * @covers \Chubbyphp\ApiSkeleton\Deserialization\CourseMapping
 */
class CourseMappingTest extends TestCase
{
    public function testGetClass()
    {
        $mapping = new CourseMapping();

        self::assertSame(Course::class, $mapping->getClass());
    }

    public function testGetFactory()
    {
        $mapping = new CourseMapping();

        self::assertSame([Course::class, 'create'], $mapping->getFactory());
    }

    public function testGetPropertyMappings()
    {
        $mapping = new CourseMapping();

        self::assertEquals([
            new PropertyMapping('name'),
            new PropertyMapping('level'),
            new PropertyMapping('progress'),
            new PropertyMapping('active'),
            new PropertyMapping('documents', new PropertyModelCollectionDeserializer(Document::class, true)),
        ], $mapping->getPropertyMappings());
    }
}
