<?php

declare(strict_types=1);

namespace Chubbyphp\ApiSkeleton\Validation;

use Chubbyphp\ApiSkeleton\Model\Document;
use Chubbyphp\Model\Collection\ModelCollectionInterface;
use Chubbyphp\Model\ResolverInterface;
use Chubbyphp\Validation\Constraint\CallbackConstraint;
use Chubbyphp\Validation\Constraint\ChoiceConstraint;
use Chubbyphp\Validation\Constraint\ConstraintInterface;
use Chubbyphp\Validation\Constraint\NotBlankConstraint;
use Chubbyphp\Validation\Constraint\NotNullConstraint;
use Chubbyphp\Validation\Constraint\NumericConstraint;
use Chubbyphp\Validation\Constraint\NumericRangeConstraint;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Mapping\ObjectMappingInterface;
use Chubbyphp\Validation\Mapping\PropertyMapping;
use Chubbyphp\Validation\Mapping\PropertyMappingInterface;
use Chubbyphp\ApiSkeleton\Model\Course;
use Chubbyphp\ValidationModel\Constraint\ModelCollectionConstraint;
use Chubbyphp\ValidationModel\Constraint\UniqueModelConstraint;

final class CourseMapping implements ObjectMappingInterface
{
    /**
     * @var ResolverInterface
     */
    private $resolver;

    /**
     * @param ResolverInterface $resolver
     */
    public function __construct(ResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return Course::class;
    }

    /**
     * @return ConstraintInterface[]
     */
    public function getConstraints(): array
    {
        return [new UniqueModelConstraint($this->resolver, ['name'])];
    }

    /**
     * @return PropertyMappingInterface[]
     */
    public function getPropertyMappings(): array
    {
        return [
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
        ];
    }
}
