<?php

namespace Chubbyphp\Tests\ApiSkeleton\Unit\Serialization;

use Chubbyphp\Serialization\Accessor\PropertyAccessor;
use Chubbyphp\Serialization\Mapping\FieldMapping;
use Chubbyphp\Serialization\Serializer\Field\CollectionFieldSerializer;
use Chubbyphp\Serialization\Serializer\Field\ValueFieldSerializer;
use Chubbyphp\ApiSkeleton\Search\CourseSearch;
use Chubbyphp\ApiSkeleton\Serialization\CourseSearchMapping;

/**
 * @covers \Chubbyphp\ApiSkeleton\Serialization\CourseSearchMapping
 */
class CourseSearchMappingTest extends AbstractMappingTest
{
    public function testClass()
    {
        $mapping = new CourseSearchMapping($this->getLinkGenerator());

        self::assertSame(CourseSearch::class, $mapping->getClass());
    }

    public function testGetType()
    {
        $mapping = new CourseSearchMapping($this->getLinkGenerator());

        self::assertSame('course-search', $mapping->getType());
    }

    public function testGetFieldMappings()
    {
        $mapping = new CourseSearchMapping($this->getLinkGenerator());

        self::assertEquals([
            new FieldMapping('page', new ValueFieldSerializer(
                new PropertyAccessor('page'),
                ValueFieldSerializer::CAST_INT)
            ),
            new FieldMapping('perPage', new ValueFieldSerializer(
                new PropertyAccessor('perPage'),
                ValueFieldSerializer::CAST_INT)
            ),
            new FieldMapping('sort'),
            new FieldMapping('order'),
            new FieldMapping('count'),
            new FieldMapping('pages'),
        ], $mapping->getFieldMappings());
    }

    public function testGetEmbeddedFieldMappings()
    {
        $mapping = new CourseSearchMapping($this->getLinkGenerator());

        self::assertEquals([
            new FieldMapping('courses', new CollectionFieldSerializer(new PropertyAccessor('courses'))),
        ], $mapping->getEmbeddedFieldMappings());
    }

    public function testGetLinkMappings()
    {
        $linkGenerator = $this->getLinkGenerator();
        $request = $this->getRequest();

        $mapping = new CourseSearchMapping($linkGenerator);

        $linkMappings = $mapping->getLinkMappings();

        self::assertCount(4, $linkMappings);

        $this->assertLinks(
            $linkMappings,
            $request,
            CourseSearch::create()->setPage(2)->setPerPage(10)->setPages(3)->setCount(23), [
                'page' => 2,
                'perPage' => 10,
            ],
            [
                ['name' => 'self', 'href' => '{"routeName":"course_search","data":[],"queryParams":{"page":2,"perPage":10}}'],
                ['name' => 'prev', 'href' => '{"routeName":"course_search","data":[],"queryParams":{"page":1,"perPage":10}}'],
                ['name' => 'next', 'href' => '{"routeName":"course_search","data":[],"queryParams":{"page":3,"perPage":10}}'],
                ['name' => 'create', 'href' => '{"routeName":"course_create","data":[],"queryParams":[]}'],
            ]
        );
    }

    public function testGetLinkMappingsWithoutPrevOrNext()
    {
        $linkGenerator = $this->getLinkGenerator();
        $request = $this->getRequest();

        $mapping = new CourseSearchMapping($linkGenerator);

        $linkMappings = $mapping->getLinkMappings();

        self::assertCount(4, $linkMappings);

        $this->assertLinks(
            $linkMappings,
            $request,
            CourseSearch::create()->setPage(1)->setPerPage(10)->setPages(1)->setCount(9), [
                'page' => 1,
                'perPage' => 10,
            ],
            [
                ['name' => 'self', 'href' => '{"routeName":"course_search","data":[],"queryParams":{"page":1,"perPage":10}}'],
                ['name' => 'prev', 'href' => null],
                ['name' => 'next', 'href' => null],
                ['name' => 'create', 'href' => '{"routeName":"course_create","data":[],"queryParams":[]}'],
            ]
        );
    }
}
