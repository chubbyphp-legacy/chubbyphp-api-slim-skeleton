<?php

namespace Chubbyphp\Tests\ApiSkeleton\Unit\Model;

use Chubbyphp\ApiSkeleton\Model\Document;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\ApiSkeleton\Model\Document
 */
class DocumentTest extends TestCase
{
    public function testGetSet()
    {
        $course = Document::create('id1')
            ->setName('Choose the right abstraction')
            ->setUrl('http://test.com/choose-the-right-abstractions.pdf');

        self::assertSame('id1', $course->getId());
        self::assertSame('Choose the right abstraction', $course->getName());
        self::assertSame('http://test.com/choose-the-right-abstractions.pdf', $course->getUrl());
    }

    public function testFromAndToPersistence()
    {
        $course = Document::fromPersistence([
            'id' => 'id1',
            'courseId' => null,
            'name' => 'Choose the right abstraction',
            'url' => 'http://test.com/choose-the-right-abstractions.pdf',
        ]);

        self::assertEquals([
            'id' => 'id1',
            'courseId' => null,
            'name' => 'Choose the right abstraction',
            'url' => 'http://test.com/choose-the-right-abstractions.pdf',
        ], $course->toPersistence());
    }
}
