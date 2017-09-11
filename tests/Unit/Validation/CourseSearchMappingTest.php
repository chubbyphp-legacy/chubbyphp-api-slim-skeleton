<?php

namespace Chubbyphp\Tests\ApiSkeleton\Unit\Validation;

use Chubbyphp\Validation\Constraint\ChoiceConstraint;
use Chubbyphp\Validation\Constraint\NotNullConstraint;
use Chubbyphp\Validation\Constraint\NumericConstraint;
use Chubbyphp\Validation\Constraint\NumericRangeConstraint;
use Chubbyphp\Validation\Mapping\PropertyMapping;
use PHPUnit\Framework\TestCase;
use Chubbyphp\ApiSkeleton\Search\CourseSearch;
use Chubbyphp\ApiSkeleton\Validation\CourseSearchMapping;

/**
 * @covers \Chubbyphp\ApiSkeleton\Validation\CourseSearchMapping
 */
class CourseSearchMappingTest extends TestCase
{
    public function testGetClass()
    {
        $mapping = new CourseSearchMapping();

        self::assertSame(CourseSearch::class, $mapping->getClass());
    }

    public function testGetConstraints()
    {
        $mapping = new CourseSearchMapping();

        self::assertEquals([], $mapping->getConstraints());
    }

    public function testGetPropertyMappings()
    {
        $mapping = new CourseSearchMapping();

        self::assertEquals([
            new PropertyMapping('page', [
                new NotNullConstraint(),
                new NumericConstraint(),
                new NumericRangeConstraint(1),
            ]),
            new PropertyMapping('perPage', [
                new NotNullConstraint(),
                new NumericConstraint(),
                new NumericRangeConstraint(1),
            ]),
            new PropertyMapping('sort', [
                new ChoiceConstraint(ChoiceConstraint::TYPE_STRING, CourseSearch::SORT),
            ]),
            new PropertyMapping('order', [
                new NotNullConstraint(),
                new ChoiceConstraint(ChoiceConstraint::TYPE_STRING, CourseSearch::ORDER),
            ]),
        ], $mapping->getPropertyMappings());
    }
}
