<?php

declare(strict_types=1);

namespace Chubbyphp\ApiSkeleton\Repository;

use Chubbyphp\Model\ModelInterface;
use Chubbyphp\ApiSkeleton\Model\Document;

final class DocumentRepository extends AbstractRepository
{
    /**
     * @param array $row
     *
     * @return ModelInterface
     */
    protected function fromPersistence(array $row): ModelInterface
    {
        return Document::fromPersistence($row);
    }

    /**
     * @return string
     */
    protected function getTable(): string
    {
        return 'documents';
    }

    /**
     * @param string $modelClass
     *
     * @return bool
     */
    public function isResponsible(string $modelClass): bool
    {
        return $modelClass === Document::class;
    }
}
