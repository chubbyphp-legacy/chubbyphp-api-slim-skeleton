<?php

namespace Chubbyphp\Tests\ApiSkeleton\Unit\Repository;

use Chubbyphp\Model\ResolverInterface;
use Chubbyphp\Tests\Model\Doctrine\DBAL\TestHelperTraits\GetConnectionTrait;
use Chubbyphp\Tests\Model\Doctrine\DBAL\TestHelperTraits\GetLoggerTrait;
use Chubbyphp\Tests\Model\Doctrine\DBAL\TestHelperTraits\GetStorageCacheTrait;
use PHPUnit\Framework\TestCase;
use Chubbyphp\ApiSkeleton\Model\Document;
use Chubbyphp\ApiSkeleton\Repository\DocumentRepository;

/**
 * @covers \Chubbyphp\ApiSkeleton\Repository\DocumentRepository
 */
class DocumentRepositoryTest extends TestCase
{
    use GetConnectionTrait;
    use GetLoggerTrait;
    use GetStorageCacheTrait;

    public function testFromPersistence()
    {
        $repository = new DocumentRepository(
            $this->getConnection(),
            $this->getResolver(),
            $this->getStorageCache(),
            $this->getLogger()
        );

        self::assertTrue($repository->isResponsible(Document::class));
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
