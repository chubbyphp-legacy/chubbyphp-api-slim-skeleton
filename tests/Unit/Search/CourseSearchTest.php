<?php

namespace Chubbyphp\Tests\ApiSkeleton\Unit\Search;

use PHPUnit\Framework\TestCase;
use Chubbyphp\ApiSkeleton\Model\Course;
use Chubbyphp\ApiSkeleton\Search\CourseSearch;

/**
 * @covers \Chubbyphp\ApiSkeleton\Search\CourseSearch
 */
class CourseSearchTest extends TestCase
{
    public function testGetSetMinial()
    {
        $courseSearch = CourseSearch::create();
        $courseSearch->setPage(1);

        self::assertSame(1, $courseSearch->getPage());
        self::assertSame(20, $courseSearch->getPerPage());
        self::assertNull($courseSearch->getSort());
        self::assertSame('asc', $courseSearch->getOrder());
    }

    public function testGetSet()
    {
        $courseSearch = CourseSearch::create();
        $courseSearch->setPage(1);
        $courseSearch->setPerPage(10);
        $courseSearch->setSort('name');
        $courseSearch->setOrder(CourseSearch::ORDER_DESC);

        self::assertSame(1, $courseSearch->getPage());
        self::assertSame(10, $courseSearch->getPerPage());
        self::assertSame('name', $courseSearch->getSort());
        self::assertSame('desc', $courseSearch->getOrder());
    }

    public function testRunTimeData()
    {
        $courseSearch = CourseSearch::create();
        $courseSearch->setCount(1);
        $courseSearch->setPages(1);
        $courseSearch->setCourses([Course::create('id1')]);

        self::assertSame(1, $courseSearch->getCount());
        self::assertSame(1, $courseSearch->getPages());
        self::assertEquals([Course::create('id1')], $courseSearch->getCourses());
    }
}
