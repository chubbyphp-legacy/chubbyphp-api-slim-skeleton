<?php

namespace Chubbyphp\Tests\ApiSkeleton\Unit\Validation;

use Chubbyphp\ApiSkeleton\Model\Document;
use Chubbyphp\Model\Collection\ModelCollectionInterface;
use Chubbyphp\Model\ResolverInterface;
use Chubbyphp\Validation\Constraint\CallbackConstraint;
use Chubbyphp\Validation\Constraint\ChoiceConstraint;
use Chubbyphp\Validation\Constraint\NotBlankConstraint;
use Chubbyphp\Validation\Constraint\NotNullConstraint;
use Chubbyphp\Validation\Constraint\NumericConstraint;
use Chubbyphp\Validation\Constraint\NumericRangeConstraint;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Mapping\PropertyMapping;
use Chubbyphp\ValidationModel\Constraint\ModelCollectionConstraint;
use Chubbyphp\ValidationModel\Constraint\UniqueModelConstraint;
use PHPUnit\Framework\TestCase;
use Chubbyphp\ApiSkeleton\Model\Course;
use Chubbyphp\ApiSkeleton\Validation\CourseMapping;

/**
 * @covers \Chubbyphp\ApiSkeleton\Validation\CourseMapping
 */
class CourseMappingTest extends TestCase
{
    public function testGetClass()
    {
        $mapping = new CourseMapping($this->getResolver());

        self::assertSame(Course::class, $mapping->getClass());
    }

    public function testGetConstraints()
    {
        $resolver = $this->getResolver();

        $mapping = new CourseMapping($resolver);

        self::assertEquals([
            new UniqueModelConstraint($resolver, ['name']),
        ], $mapping->getConstraints());
    }

    public function testGetPropertyMappings()
    {
        $resolver = $this->getResolver();

        $mapping = new CourseMapping($resolver);

        self::assertEquals([
            new PropertyMapping('name', [new NotNullConstraint(), new NotBlankConstraint()]),
            new PropertyMapping('level', [
                new NotNullConstraint(),
                new ChoiceConstraint(
                    ChoiceConstraint::TYPE_INT,
                    Course::LEVEL
                ),
            ]),
            new PropertyMapping('progress', [
                new NotNullConstraint(),
                new NumericConstraint(),
                new NumericRangeConstraint(0, 1),
            ]),
            new PropertyMapping('active', [new NotNullConstraint(), new NotBlankConstraint()]),
            new PropertyMapping('documents', [
                new ModelCollectionConstraint(),
                new CallbackConstraint(function (string $path, $collection) {
                    if (!$collection instanceof ModelCollectionInterface) {
                        return [];
                    }

                    $names = [];
                    foreach ($collection->getModels() as $document) {
                        /** @var Document $document */
                        if (isset($names[$document->getName()])) {
                            return [
                                new Error(
                                    $path.'[_all]',
                                    'constraint.uniquemodel.notunique',
                                     ['uniqueProperties' => 'name']
                                ),
                            ];
                        }

                        $names[$document->getName()] = true;
                    }

                    return [];
                }),
            ]),
        ], $mapping->getPropertyMappings());
    }

    /**
     * @return ResolverInterface
     */
    private function getResolver(): ResolverInterface
    {
        /** @var ResolverInterface|\PHPUnit_Framework_MockObject_MockObject $resolver */
        $resolver = $this->getMockBuilder(ResolverInterface::class)->getMockForAbstractClass();

        return $resolver;
    }
}
