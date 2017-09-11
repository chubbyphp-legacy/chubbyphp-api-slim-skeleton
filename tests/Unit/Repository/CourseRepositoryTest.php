<?php

namespace Chubbyphp\Tests\ApiSkeleton\Unit\Repository;

use Chubbyphp\Model\ResolverInterface;
use Chubbyphp\Tests\Model\Doctrine\DBAL\TestHelperTraits\GetConnectionTrait;
use Chubbyphp\Tests\Model\Doctrine\DBAL\TestHelperTraits\GetLoggerTrait;
use Chubbyphp\Tests\Model\Doctrine\DBAL\TestHelperTraits\GetStorageCacheTrait;
use PHPUnit\Framework\TestCase;
use Chubbyphp\ApiSkeleton\Model\Course;
use Chubbyphp\ApiSkeleton\Repository\CourseRepository;
use Chubbyphp\ApiSkeleton\Search\CourseSearch;

/**
 * @covers \Chubbyphp\ApiSkeleton\Repository\CourseRepository
 */
class CourseRepositoryTest extends TestCase
{
    use GetConnectionTrait;
    use GetLoggerTrait;
    use GetStorageCacheTrait;

    public function testFromPersistence()
    {
        $repository = new CourseRepository(
            $this->getConnection(),
            $this->getResolver(),
            $this->getStorageCache(),
            $this->getLogger()
        );

        self::assertTrue($repository->isResponsible(Course::class));
    }

    public function testSearch()
    {
        $queryBuilderCount = $this->getQueryBuilder([$this->getStatement(\PDO::FETCH_COLUMN, 1)]);
        $queryBuilderSearch = $this->getQueryBuilder([$this->getStatement(\PDO::FETCH_ASSOC, [
            [
                'id' => 'id1',
                'name' => 'PHP Course',
                'level' => 3,
                'progress' => 0,
                'active' => true,
            ],
        ])]);

        $connection = $this->getConnection(['queryBuilder' => [
            $queryBuilderCount,
            $queryBuilderSearch,
        ]]);

        $repository = new CourseRepository(
            $connection,
            $this->getResolver(),
            $this->getStorageCache(),
            $this->getLogger()
        );

        $courseSearch = CourseSearch::create();
        $courseSearch->setSort(CourseSearch::SORT_NAME);
        $courseSearch->setOrder(CourseSearch::ORDER_DESC);

        self::assertSame($courseSearch, $repository->search($courseSearch));

        self::assertSame(1, $courseSearch->getPages());
        self::assertSame(1, $courseSearch->getCount());

        $course = $courseSearch->getCourses()[0];

        self::assertSame('id1', $course->getId());
        self::assertSame('PHP Course', $course->getName());
        self::assertSame(Course::LEVEL_ADVANCED, $course->getLevel());
        self::assertSame(0.0, $course->getProgress());
        self::assertTrue($course->isActive());
        self::assertSame([], $course->getDocuments());

        self::assertEquals(
            [
                'from' => [
                    [
                        'courses',
                        null,
                    ],
                ],
                'select' => [
                    [
                        'COUNT(id) AS rowCount',
                    ],
                ],
            ],
            $queryBuilderCount->__calls
        );

        self::assertEquals(
            [
                'from' => [
                    [
                        'courses',
                        null,
                    ],
                ],
                'select' => [
                    [
                        '*',
                    ],
                ],
                'setFirstResult' => [
                    [
                        0,
                    ],
                ],
                'setMaxResults' => [
                    [
                        20,
                    ],
                ],
                'orderBy' => [
                    [
                        CourseSearch::SORT_NAME,
                        CourseSearch::ORDER_DESC,
                    ],
                ],
            ],
            $queryBuilderSearch->__calls
        );
    }

    /**
     * @return ResolverInterface
     */
    private function getResolver(): ResolverInterface
    {
        /** @var ResolverInterface|\PHPUnit_Framework_MockObject_MockObject $resolver */
        $resolver = $this->getMockBuilder(ResolverInterface::class)->setMethods([])->getMockForAbstractClass();

        return $resolver;
    }
}
