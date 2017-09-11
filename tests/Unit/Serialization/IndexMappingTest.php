<?php

namespace Chubbyphp\Tests\ApiSkeleton\Unit\Serialization;

use Chubbyphp\ApiSkeleton\Search\Index;
use Chubbyphp\ApiSkeleton\Serialization\IndexMapping;

/**
 * @covers \Chubbyphp\ApiSkeleton\Serialization\IndexMapping
 */
class IndexMappingTest extends AbstractMappingTest
{
    public function testClass()
    {
        $mapping = new IndexMapping($this->getLinkGenerator());

        self::assertSame(Index::class, $mapping->getClass());
    }

    public function testGetType()
    {
        $mapping = new IndexMapping($this->getLinkGenerator());

        self::assertSame('index', $mapping->getType());
    }

    public function testGetFieldMappings()
    {
        $mapping = new IndexMapping($this->getLinkGenerator());

        self::assertEquals([], $mapping->getFieldMappings());
    }

    public function testGetEmbeddedFieldMappings()
    {
        $mapping = new IndexMapping($this->getLinkGenerator());

        self::assertEquals([], $mapping->getEmbeddedFieldMappings());
    }

    public function testGetLinkMappings()
    {
        $linkGenerator = $this->getLinkGenerator();
        $request = $this->getRequest();

        $mapping = new IndexMapping($linkGenerator);

        $linkMappings = $mapping->getLinkMappings();

        self::assertCount(2, $linkMappings);

        $this->assertLinks($linkMappings, $request, new Index(), [], [
            ['name' => 'self', 'href' => '{"routeName":"index","data":[],"queryParams":[]}'],
            ['name' => 'courses', 'href' => '{"routeName":"course_search","data":[],"queryParams":[]}'],
        ]);
    }
}
