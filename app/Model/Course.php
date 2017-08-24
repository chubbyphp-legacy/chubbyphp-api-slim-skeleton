<?php

declare(strict_types=1);

namespace Chubbyphp\ApiSkeleton\Model;

use Chubbyphp\Model\Collection\ModelCollection;
use Chubbyphp\Model\Collection\ModelCollectionInterface;
use Chubbyphp\Model\ModelInterface;
use Ramsey\Uuid\Uuid;

final class Course implements ModelInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $level;

    const LEVEL_START = 1;
    const LEVEL_MEDIUM = 2;
    const LEVEL_ADVANCED = 3;
    const LEVEL_EXPERT = 4;

    const LEVEL = [
        self::LEVEL_START,
        self::LEVEL_MEDIUM,
        self::LEVEL_ADVANCED,
        self::LEVEL_EXPERT,
    ];

    /**
     * @var float
     */
    private $progress;

    /**
     * @var bool
     */
    private $active;

    /**
     * @var ModelCollectionInterface
     */
    private $documents;

    private function __construct()
    {
    }

    /**
     * @param string|null $id
     *
     * @return self
     */
    public static function create(string $id = null): self
    {
        $self = new self();
        $self->id = $id ?? (string) Uuid::uuid4();
        $self->documents = new ModelCollection(
            Document::class, 'courseId', $self->id, ['name' => 'ASC']
        );

        return $self;
    }

    /**
     * @param array $data
     *
     * @return self|ModelInterface
     */
    public static function fromPersistence(array $data): ModelInterface
    {
        $self = new self();
        $self->id = $data['id'];
        $self->name = $data['name'];
        $self->level = (int) $data['level'];
        $self->progress = (float) $data['progress'];
        $self->active = (bool) $data['active'];
        $self->documents = $data['documents'];

        return $self;
    }

    /**
     * @return array
     */
    public function toPersistence(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'level' => $this->level,
            'progress' => $this->progress,
            'active' => $this->active,
            'documents' => $this->documents,
        ];
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @param int $level
     *
     * @return self
     */
    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    /**
     * @return float
     */
    public function getProgress(): float
    {
        return $this->progress;
    }

    /**
     * @param float $progress
     *
     * @return self
     */
    public function setProgress(float $progress): self
    {
        $this->progress = $progress;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     *
     * @return self
     */
    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @param array $documents
     *
     * @return self
     */
    public function setDocuments(array $documents): self
    {
        $this->documents->setModels($documents);

        return $this;
    }

    /**
     * @return Document[]|array
     */
    public function getDocuments(): array
    {
        return array_values($this->documents->getModels());
    }
}
