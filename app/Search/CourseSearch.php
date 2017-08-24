<?php

declare(strict_types=1);

namespace Chubbyphp\ApiSkeleton\Search;

use Chubbyphp\ApiSkeleton\Model\Course;

final class CourseSearch
{
    /**
     * @var int
     */
    private $page = 1;

    /**
     * @var int
     */
    private $perPage = 20;

    /**
     * @var string|null
     */
    private $sort;

    const SORT = [];

    /**
     * @var string
     */
    private $order = self::ORDER_ASC;

    const ORDER_ASC = 'asc';
    const ORDER_DESC = 'desc';

    const ORDER = [
        self::ORDER_ASC,
        self::ORDER_DESC,
    ];

    /**
     * @var int
     */
    private $pages;

    /**
     * @var int
     */
    private $count;

    /**
     * @var Course[]
     */
    private $courses;

    private function __construct()
    {
    }

    /**
     * @return CourseSearch
     */
    public static function create(): self
    {
        return new self();
    }

    /**
     * @return int|null
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int $page
     *
     * @return self
     */
    public function setPage(int $page): self
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @return int
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    /**
     * @param int $perPage
     *
     * @return self
     */
    public function setPerPage(int $perPage): self
    {
        $this->perPage = $perPage;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param null|string $sort
     *
     * @return self
     */
    public function setSort(string $sort = null): self
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param null|string $order
     *
     * @return self
     */
    public function setOrder(string $order = null): self
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return int
     */
    public function getPages(): int
    {
        return $this->pages;
    }

    /**
     * @param int $pages
     */
    public function setPages(int $pages)
    {
        $this->pages = $pages;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @param int $count
     */
    public function setCount(int $count)
    {
        $this->count = $count;
    }

    /**
     * @return Course[]
     */
    public function getCourses(): array
    {
        return $this->courses;
    }

    /**
     * @param Course[] $courses
     */
    public function setCourses(array $courses)
    {
        $this->courses = $courses;
    }
}
