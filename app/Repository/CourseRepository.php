<?php

declare(strict_types=1);

namespace Chubbyphp\ApiSkeleton\Repository;

use Chubbyphp\ApiSkeleton\Model\Document;
use Chubbyphp\Model\Collection\LazyModelCollection;
use Chubbyphp\Model\ModelInterface;
use Doctrine\DBAL\Query\QueryBuilder;
use Chubbyphp\ApiSkeleton\Model\Course;
use Chubbyphp\ApiSkeleton\Search\CourseSearch;

final class CourseRepository extends AbstractRepository
{
    /**
     * @param array $row
     *
     * @return ModelInterface
     */
    protected function fromPersistence(array $row): ModelInterface
    {
        $row['documents'] = new LazyModelCollection(
            $this->resolver, Document::class, 'courseId', $row['id'], ['name' => 'ASC']
        );

        return Course::fromPersistence($row);
    }

    /**
     * @return string
     */
    protected function getTable(): string
    {
        return 'courses';
    }

    /**
     * @param string $modelClass
     *
     * @return bool
     */
    public function isResponsible(string $modelClass): bool
    {
        return $modelClass === Course::class;
    }

    /**
     * @param CourseSearch $courseSearch
     *
     * @return CourseSearch
     */
    public function search(CourseSearch $courseSearch): CourseSearch
    {
        $count = $this->searchCount();

        $courseSearch->setPages((int) ceil($count / $courseSearch->getPerPage()));
        $courseSearch->setCount($count);
        $courseSearch->setCourses($this->searchResult($courseSearch));

        return $courseSearch;
    }

    /**
     * @return int
     */
    private function searchCount(): int
    {
        $qb = $this->prepareSearchQuery();
        $qb->select('COUNT(id) AS rowCount');

        return (int) $qb->execute()->fetchColumn();
    }

    /**
     * @param CourseSearch $courseSearch
     *
     * @return array
     */
    private function searchResult(CourseSearch $courseSearch): array
    {
        $qb = $this->prepareSearchQuery();

        $perPage = $courseSearch->getPerPage();

        $qb->select('*');
        $qb->setFirstResult($courseSearch->getPage() * $perPage - $perPage);
        $qb->setMaxResults($perPage);

        if (null !== $sort = $courseSearch->getSort()) {
            $qb->orderBy($sort, $courseSearch->getOrder());
        }

        $courses = [];
        foreach ($qb->execute()->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            $courses[] = $this->fromPersistence($row);
        }

        return $courses;
    }

    /**
     * @return QueryBuilder
     */
    private function prepareSearchQuery(): QueryBuilder
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->from($this->getTable());

        return $qb;
    }
}
