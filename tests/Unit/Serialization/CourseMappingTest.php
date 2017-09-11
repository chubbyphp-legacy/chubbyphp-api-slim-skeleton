<?php

namespace Chubbyphp\Tests\ApiSkeleton\Unit\Serialization;

use Chubbyphp\Serialization\Accessor\MethodAccessor;
use Chubbyphp\Serialization\Mapping\FieldMapping;
use Chubbyphp\ApiSkeleton\Model\Course;
use Chubbyphp\ApiSkeleton\Serialization\CourseMapping;
use Chubbyphp\Serialization\Serializer\Field\CollectionFieldSerializer;

/**
 * @covers \Chubbyphp\ApiSkeleton\Serialization\CourseMapping
 */
class CourseMappingTest extends AbstractMappingTest
{
    public function testClass()
    {
        $mapping = new CourseMapping($this->getLinkGenerator());

        self::assertSame(Course::class, $mapping->getClass());
    }

    public function testGetType()
    {
        $mapping = new CourseMapping($this->getLinkGenerator());

        self::assertSame('course', $mapping->getType());
    }

    public function testGetFieldMappings()
    {
        $mapping = new CourseMapping($this->getLinkGenerator());

        self::assertEquals([
            new FieldMapping('id'),
            new FieldMapping('name'),
            new FieldMapping('level'),
            new FieldMapping('progress'),
            new FieldMapping('active'),
            new FieldMapping('documents', new CollectionFieldSerializer(new MethodAccessor('getDocuments'))),
        ], $mapping->getFieldMappings());
    }

    public function testGetEmbeddedFieldMappings()
    {
        $mapping = new CourseMapping($this->getLinkGenerator());

        self::assertSame([], $mapping->getEmbeddedFieldMappings());
    }

    public function testGetLinkMappings()
    {
        $linkGenerator = $this->getLinkGenerator();
        $request = $this->getRequest(['route' => $this->getRoute('name', [])]);

        $mapping = new CourseMapping($linkGenerator);

        $linkMappings = $mapping->getLinkMappings();

        self::assertCount(3, $linkMappings);

        $this->assertLinks($linkMappings, $request, Course::create('id1'), [], [
            ['name' => 'read', 'href' => '{"routeName":"course_read","data":{"id":"id1"},"queryParams":[]}'],
            ['name' => 'update', 'href' => '{"routeName":"course_update","data":{"id":"id1"},"queryParams":[]}'],
            ['name' => 'delete', 'href' => '{"routeName":"course_delete","data":{"id":"id1"},"queryParams":[]}'],
        ]);
    }
}
