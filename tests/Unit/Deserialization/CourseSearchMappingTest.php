<?php

namespace Chubbyphp\Tests\ApiSkeleton\Unit\Deserialization;

use Chubbyphp\Deserialization\Mapping\PropertyMapping;
use PHPUnit\Framework\TestCase;
use Chubbyphp\ApiSkeleton\Deserialization\CourseSearchMapping;
use Chubbyphp\ApiSkeleton\Search\CourseSearch;

/**
 * @covers \Chubbyphp\ApiSkeleton\Deserialization\CourseSearchMapping
 */
class CourseSearchMappingTest extends TestCase
{
    public function testGetClass()
    {
        $mapping = new CourseSearchMapping();

        self::assertSame(CourseSearch::class, $mapping->getClass());
    }

    public function testGetFactory()
    {
        $mapping = new CourseSearchMapping();

        self::assertSame([CourseSearch::class, 'create'], $mapping->getFactory());
    }

    public function testGetPropertyMappings()
    {
        $mapping = new CourseSearchMapping();

        self::assertEquals([
            new PropertyMapping('page'),
            new PropertyMapping('perPage'),
            new PropertyMapping('sort'),
            new PropertyMapping('order'),
        ], $mapping->getPropertyMappings());
    }
}
