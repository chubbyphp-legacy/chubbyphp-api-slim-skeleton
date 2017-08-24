<?php

declare(strict_types=1);

namespace Chubbyphp\ApiSkeleton\Model;

use Chubbyphp\Model\ModelInterface;
use Ramsey\Uuid\Uuid;

final class Document implements ModelInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $courseId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $url;

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
        $self->courseId = $data['courseId'];
        $self->name = $data['name'];
        $self->url = $data['url'];

        return $self;
    }

    /**
     * @return array
     */
    public function toPersistence(): array
    {
        return [
            'id' => $this->id,
            'courseId' => $this->courseId,
            'name' => $this->name,
            'url' => $this->url,
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
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return self
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }
}
