<?php

namespace Chubbyphp\Tests\ApiSkeleton\Integration\Controller;

use Chubbyphp\Tests\ApiSkeleton\Integration\AbstractIntegrationTest;

class CourseControllerTest extends AbstractIntegrationTest
{
    public function testSearch()
    {
        $response = $this->httpRequest('GET', '/api/courses', ['Accept' => 'application/json']);

        self::assertSame(200, $response['status']['code']);

        self::assertArrayHasKey('content-type', $response['headers']);

        self::assertSame('application/json', $response['headers']['content-type'][0]);

        $data = json_decode($response['body'], true);

        self::assertSame(1, $data['page']);
        self::assertSame(20, $data['perPage']);
        self::assertNull($data['sort']);
        self::assertSame('asc', $data['order']);

        self::assertArrayHasKey('_count', $data);
        self::assertArrayHasKey('_pages', $data);
        self::assertArrayHasKey('_embedded', $data);
        self::assertArrayHasKey('courses', $data['_embedded']);
    }

    /**
     * @return string
     */
    public function testCreate()
    {
        $response = $this->httpRequest('POST', '/api/courses',
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            json_encode([
                'name' => 'PHP Course',
                'level' => 3,
                'progress' => 0,
                'active' => true,
                'documents' => [
                    [
                        'name' => 'Choose the right abstraction',
                        'url' => 'http://test.com/choose-the-right-abstractions.pdf',
                    ],
                    [
                        'name' => 'Extend vs Implements',
                        'url' => 'http://test.com/extend-vs-implements.pdf',
                    ],
                ],
            ])
        );

        self::assertSame(201, $response['status']['code']);

        self::assertArrayHasKey('content-type', $response['headers']);

        self::assertSame('application/json', $response['headers']['content-type'][0]);

        $data = json_decode($response['body'], true);

        self::assertArrayHasKey('id', $data);

        self::assertSame('PHP Course', $data['name']);
        self::assertSame(3, $data['level']);
        self::assertSame(0, $data['progress']);
        self::assertSame(true, $data['active']);

        self::assertCount(2, $data['documents']);

        $document1 = array_shift($data['documents']);

        self::assertSame('Choose the right abstraction', $document1['name']);
        self::assertSame('http://test.com/choose-the-right-abstractions.pdf', $document1['url']);

        $document2 = array_shift($data['documents']);

        self::assertSame('Extend vs Implements', $document2['name']);
        self::assertSame('http://test.com/extend-vs-implements.pdf', $document2['url']);

        self::assertArrayHasKey('_links', $data);

        self::assertArrayHasKey('read', $data['_links']);
        self::assertSame('GET', $data['_links']['read']['method']);

        self::assertArrayHasKey('update', $data['_links']);
        self::assertSame('PATCH', $data['_links']['update']['method']);

        self::assertArrayHasKey('delete', $data['_links']);
        self::assertSame('DELETE', $data['_links']['delete']['method']);

        return $data['id'];
    }

    /**
     * @depends testCreate
     */
    public function testRead(string $id)
    {
        $response = $this->httpRequest('GET', '/api/courses/'.$id,
            [
                'Accept' => 'application/json',
            ]
        );

        self::assertSame(200, $response['status']['code']);

        self::assertArrayHasKey('content-type', $response['headers']);

        self::assertSame('application/json', $response['headers']['content-type'][0]);

        $data = json_decode($response['body'], true);

        self::assertArrayHasKey('id', $data);

        self::assertSame('PHP Course', $data['name']);
        self::assertSame(3, $data['level']);
        self::assertSame(0, $data['progress']);
        self::assertSame(true, $data['active']);

        self::assertCount(2, $data['documents']);

        $document1 = array_shift($data['documents']);

        self::assertSame('Choose the right abstraction', $document1['name']);
        self::assertSame('http://test.com/choose-the-right-abstractions.pdf', $document1['url']);

        $document2 = array_shift($data['documents']);

        self::assertSame('Extend vs Implements', $document2['name']);
        self::assertSame('http://test.com/extend-vs-implements.pdf', $document2['url']);

        self::assertArrayHasKey('_links', $data);

        self::assertArrayHasKey('read', $data['_links']);
        self::assertSame('GET', $data['_links']['read']['method']);

        self::assertArrayHasKey('update', $data['_links']);
        self::assertSame('PATCH', $data['_links']['update']['method']);

        self::assertArrayHasKey('delete', $data['_links']);
        self::assertSame('DELETE', $data['_links']['delete']['method']);
    }

    /**
     * @depends testCreate
     */
    public function testUpdate(string $id)
    {
        $response = $this->httpRequest('PATCH', '/api/courses/'.$id,
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            json_encode([
                'progress' => 0.45,
                'documents' => [
                    [
                        'name' => 'Choose the right abstraction',
                        'url' => 'http://test.com/choose-the-right-abstractions.pdf',
                    ],
                ],
            ])
        );

        self::assertSame(200, $response['status']['code']);

        self::assertArrayHasKey('content-type', $response['headers']);

        self::assertSame('application/json', $response['headers']['content-type'][0]);

        $data = json_decode($response['body'], true);

        self::assertArrayHasKey('id', $data);

        self::assertSame('PHP Course', $data['name']);
        self::assertSame(3, $data['level']);
        self::assertSame(0.45, $data['progress']);
        self::assertSame(true, $data['active']);

        self::assertCount(1, $data['documents']);

        $document1 = array_shift($data['documents']);

        self::assertSame('Choose the right abstraction', $document1['name']);
        self::assertSame('http://test.com/choose-the-right-abstractions.pdf', $document1['url']);

        self::assertArrayHasKey('_links', $data);

        self::assertArrayHasKey('read', $data['_links']);
        self::assertSame('GET', $data['_links']['read']['method']);

        self::assertArrayHasKey('update', $data['_links']);
        self::assertSame('PATCH', $data['_links']['update']['method']);

        self::assertArrayHasKey('delete', $data['_links']);
        self::assertSame('DELETE', $data['_links']['delete']['method']);
    }

    /**
     * @depends testCreate
     */
    public function testDelete(string $id)
    {
        $response = $this->httpRequest('DELETE', '/api/courses/'.$id);

        self::assertSame(204, $response['status']['code']);

        self::assertNull($response['body']);
    }
}
