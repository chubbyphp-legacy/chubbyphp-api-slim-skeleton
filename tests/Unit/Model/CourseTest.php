<?php

namespace Chubbyphp\Tests\ApiSkeleton\Unit\Model;

use Chubbyphp\ApiSkeleton\Model\Document;
use Chubbyphp\Model\Collection\ModelCollection;
use PHPUnit\Framework\TestCase;
use Chubbyphp\ApiSkeleton\Model\Course;

/**
 * @covers \Chubbyphp\ApiSkeleton\Model\Course
 */
class CourseTest extends TestCase
{
    public function testGetSet()
    {
        $course = Course::create('id1');

        $course
            ->setName('PHP Course')
            ->setLevel(Course::LEVEL_ADVANCED)
            ->setProgress(0.0)
            ->setActive(true)
            ->setDocuments([
                Document::create('id2')
                    ->setName('Choose the right abstraction')
                    ->setUrl('http://test.com/choose-the-right-abstractions.pdf'),
                Document::create('id3')
                    ->setName('Extend vs Implements')
                    ->setUrl('http://test.com/extend-vs-implements.pdf'),
            ])
        ;

        self::assertSame('id1', $course->getId());
        self::assertSame('PHP Course', $course->getName());
        self::assertSame(Course::LEVEL_ADVANCED, $course->getLevel());
        self::assertSame(0.0, $course->getProgress());
        self::assertTrue($course->isActive());

        $documents = $course->getDocuments();

        self::assertCount(2, $documents);

        self::assertSame('id2', $documents[0]->getId());
        self::assertSame('Choose the right abstraction', $documents[0]->getName());
        self::assertSame('http://test.com/choose-the-right-abstractions.pdf', $documents[0]->getUrl());

        self::assertSame('id3', $documents[1]->getId());
        self::assertSame('Extend vs Implements', $documents[1]->getName());
        self::assertSame('http://test.com/extend-vs-implements.pdf', $documents[1]->getUrl());
    }

    public function testFromAndToPersistence()
    {
        $course = Course::fromPersistence([
            'id' => 'id1',
            'name' => 'PHP Course',
            'level' => Course::LEVEL_ADVANCED,
            'progress' => 0.0,
            'active' => true,
            'documents' => new ModelCollection(Document::class, 'courseId', 'id1'),
        ]);

        self::assertEquals([
            'id' => 'id1',
            'name' => 'PHP Course',
            'level' => Course::LEVEL_ADVANCED,
            'progress' => 0.0,
            'active' => true,
            'documents' => new ModelCollection(Document::class, 'courseId', 'id1'),
        ], $course->toPersistence());
    }
}
