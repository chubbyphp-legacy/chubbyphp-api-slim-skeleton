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

    const SORT_NAME = 'name';

    const SORT = [
        self::SORT_NAME,
    ];

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
     * @return int
     */
    public function getPage(): int
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
    public function getPerPage(): int
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
     * @return string
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param string $order
     *
     * @return self
     */
    public function setOrder(string $order): self
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
     *
     * @return self
     */
    public function setPages(int $pages): self
    {
        $this->pages = $pages;

        return $this;
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
     *
     * @return self
     */
    public function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
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
     *
     * @return self
     */
    public function setCourses(array $courses): self
    {
        $this->courses = $courses;

        return $this;
    }
}
