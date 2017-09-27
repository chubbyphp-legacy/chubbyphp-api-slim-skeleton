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
        self::assertSame(0, $data['count']);
        self::assertSame(0, $data['pages']);

        self::assertArrayHasKey('_embedded', $data);

        self::assertArrayHasKey('courses', $data['_embedded']);
    }

    public function testSearchWithAcceptNotSupported()
    {
        $response = $this->httpRequest('GET', '/api/courses', ['Accept' => 'application/json2']);

        self::assertSame(406, $response['status']['code']);

        self::assertArrayHasKey('x-not-acceptable', $response['headers']);
        self::assertSame(
            'accept "application/json2" is not supported, supported are application/json, application/x-www-form-urlencoded, application/xml, application/x-yaml',
            $response['headers']['x-not-acceptable'][0]
        );
    }

    public function testSearchWithValidationError()
    {
        $response = $this->httpRequest('GET', '/api/courses?page=test', ['Accept' => 'application/json']);

        self::assertSame(422, $response['status']['code']);

        self::assertArrayHasKey('content-type', $response['headers']);

        self::assertSame('application/json', $response['headers']['content-type'][0]);

        $data = json_decode($response['body'], true);

        self::assertEquals(
            [
                'scope' => 'query',
                'key' => 'validation_error',
                'detail' => 'there where validation errors while validating the object',
                'reference' => 'course-search',
                'arguments' => [
                    'page' => [
                        'The input value test is not numeric',
                    ],
                ],
                '_type' => 'error',
            ],
            $data
        );
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

    public function testCreateWithAcceptNotSupported()
    {
        $response = $this->httpRequest('POST', '/api/courses', [
            'Accept' => 'application/json2',
            'Content-Type' => 'application/json',
        ]);

        self::assertSame(406, $response['status']['code']);

        self::assertArrayHasKey('x-not-acceptable', $response['headers']);
        self::assertSame(
            'accept "application/json2" is not supported, supported are application/json, application/x-www-form-urlencoded, application/xml, application/x-yaml',
            $response['headers']['x-not-acceptable'][0]
        );
    }

    public function testCreateWithContentTypeNotSupported()
    {
        $response = $this->httpRequest('POST', '/api/courses', ['Accept' => 'application/json', 'Content-Type' => 'application/json2']);

        self::assertSame(415, $response['status']['code']);

        self::assertArrayHasKey('content-type', $response['headers']);

        self::assertSame('application/json', $response['headers']['content-type'][0]);

        $data = json_decode($response['body'], true);

        self::assertEquals(
            [
                'scope' => 'header',
                'key' => 'contentype_not_supported',
                'detail' => 'the given content type is not supported',
                'reference' => 'content-type',
                'arguments' => [
                    'contentType' => 'application/json2',
                    'supportedContentTypes' => [
                        'application/json',
                        'application/x-www-form-urlencoded',
                        'application/xml',
                        'application/x-yaml',
                    ],
                ],
                '_type' => 'error',
            ],
            $data
        );
    }

    public function testCreateWithBodyNotDeserializable()
    {
        $response = $this->httpRequest('POST', '/api/courses', [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);

        self::assertSame(400, $response['status']['code']);

        self::assertArrayHasKey('content-type', $response['headers']);

        self::assertSame('application/json', $response['headers']['content-type'][0]);

        $data = json_decode($response['body'], true);

        self::assertEquals(
            [
                'scope' => 'body',
                'key' => 'body_not_deserializable',
                'detail' => 'the given body is not deserializable with given content-type',
                'reference' => 'deserialize',
                'arguments' => [
                    'contentType' => 'application/json',
                    'body' => '',
                ],
                '_type' => 'error',
            ],
            $data
        );
    }

    public function testCreateWithValidationError()
    {
        $response = $this->httpRequest('POST', '/api/courses',
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            json_encode([])
        );

        self::assertSame(422, $response['status']['code']);

        self::assertArrayHasKey('content-type', $response['headers']);

        self::assertSame('application/json', $response['headers']['content-type'][0]);

        $data = json_decode($response['body'], true);

        self::assertEquals(
            [
                'scope' => 'body',
                'key' => 'validation_error',
                'detail' => 'there where validation errors while validating the object',
                'reference' => 'course',
                'arguments' => [
                    'name' => [
                        'This value can\'t be null',
                    ],
                    'level' => [
                        'This value can\'t be null',
                    ],
                    'progress' => [
                        'This value can\'t be null',
                    ],
                    'active' => [
                        'This value can\'t be null',
                    ],
                ],
                '_type' => 'error',
            ],
            $data
        );
    }

    /**
     * @depends testCreate
     */
    public function testRead(string $id)
    {
        $response = $this->httpRequest('GET', '/api/courses/'.$id, [
            'Accept' => 'application/json',
        ]);

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
    public function testReadWithAcceptNotSupported(string $id)
    {
        $response = $this->httpRequest('GET', '/api/courses/'.$id, [
            'Accept' => 'application/json2',
        ]);

        self::assertSame(406, $response['status']['code']);

        self::assertArrayHasKey('x-not-acceptable', $response['headers']);
        self::assertSame(
            'accept "application/json2" is not supported, supported are application/json, application/x-www-form-urlencoded, application/xml, application/x-yaml',
            $response['headers']['x-not-acceptable'][0]
        );
    }

    public function testReadWithResourceNotFound()
    {
        $response = $this->httpRequest('GET', '/api/courses/ccd8edff-87a5-4aa9-9c77-663ccfd7cdca', [
            'Accept' => 'application/json',
        ]);

        self::assertSame(404, $response['status']['code']);

        self::assertArrayHasKey('content-type', $response['headers']);

        self::assertSame('application/json', $response['headers']['content-type'][0]);

        $data = json_decode($response['body'], true);

        self::assertEquals(
            [
                'scope' => 'resource',
                'key' => 'resource_not_found',
                'detail' => 'the wished resource does not exist',
                'reference' => 'course',
                'arguments' => [
                    'id' => 'ccd8edff-87a5-4aa9-9c77-663ccfd7cdca',
                ],
                '_type' => 'error',
            ],
            $data
        );
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
    public function testUpdateWithAcceptNotSupported(string $id)
    {
        $response = $this->httpRequest('PATCH', '/api/courses/'.$id, [
            'Accept' => 'application/json2',
        ]);

        self::assertSame(406, $response['status']['code']);

        self::assertArrayHasKey('x-not-acceptable', $response['headers']);
        self::assertSame(
            'accept "application/json2" is not supported, supported are application/json, application/x-www-form-urlencoded, application/xml, application/x-yaml',
            $response['headers']['x-not-acceptable'][0]
        );
    }

    /**
     * @depends testCreate
     */
    public function testUpdateWithContentTypeNotSupported(string $id)
    {
        $response = $this->httpRequest('PATCH', '/api/courses/'.$id, [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json2',
        ]);

        self::assertSame(415, $response['status']['code']);

        self::assertArrayHasKey('content-type', $response['headers']);

        self::assertSame('application/json', $response['headers']['content-type'][0]);

        $data = json_decode($response['body'], true);

        self::assertEquals(
            [
                'scope' => 'header',
                'key' => 'contentype_not_supported',
                'detail' => 'the given content type is not supported',
                'reference' => 'content-type',
                'arguments' => [
                    'contentType' => 'application/json2',
                    'supportedContentTypes' => [
                        'application/json',
                        'application/x-www-form-urlencoded',
                        'application/xml',
                        'application/x-yaml',
                    ],
                ],
                '_type' => 'error',
            ],
            $data
        );
    }

    public function testUpdateWithResourceNotFound()
    {
        $response = $this->httpRequest('PATCH', '/api/courses/ccd8edff-87a5-4aa9-9c77-663ccfd7cdca', [
            'Accept' => 'application/json',
        ]);

        self::assertSame(404, $response['status']['code']);

        self::assertArrayHasKey('content-type', $response['headers']);

        self::assertSame('application/json', $response['headers']['content-type'][0]);

        $data = json_decode($response['body'], true);

        self::assertEquals(
            [
                'scope' => 'resource',
                'key' => 'resource_not_found',
                'detail' => 'the wished resource does not exist',
                'reference' => 'course',
                'arguments' => [
                    'id' => 'ccd8edff-87a5-4aa9-9c77-663ccfd7cdca',
                ],
                '_type' => 'error',
            ],
            $data
        );
    }

    /**
     * @depends testCreate
     */
    public function testUpdateWithBodyNotDeserializable(string $id)
    {
        $response = $this->httpRequest('PATCH', '/api/courses/'.$id, [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);

        self::assertSame(400, $response['status']['code']);

        self::assertArrayHasKey('content-type', $response['headers']);

        self::assertSame('application/json', $response['headers']['content-type'][0]);

        $data = json_decode($response['body'], true);

        self::assertEquals(
            [
                'scope' => 'body',
                'key' => 'body_not_deserializable',
                'detail' => 'the given body is not deserializable with given content-type',
                'reference' => 'deserialize',
                'arguments' => [
                    'contentType' => 'application/json',
                    'body' => '',
                ],
                '_type' => 'error',
            ],
            $data
        );
    }

    /**
     * @depends testCreate
     */
    public function testUpdateWithValidationError(string $id)
    {
        $response = $this->httpRequest('PATCH', '/api/courses/'.$id,
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            json_encode(['name' => null])
        );

        self::assertSame(422, $response['status']['code']);

        self::assertArrayHasKey('content-type', $response['headers']);

        self::assertSame('application/json', $response['headers']['content-type'][0]);

        $data = json_decode($response['body'], true);

        self::assertEquals(
            [
                'scope' => 'body',
                'key' => 'validation_error',
                'detail' => 'there where validation errors while validating the object',
                'reference' => 'course',
                'arguments' => [
                    'name' => [
                        'This value can\'t be null',
                    ],
                ],
                '_type' => 'error',
            ],
            $data
        );
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

    /**
     * @depends testCreate
     */
    public function testDeleteWithAcceptNotSupported(string $id)
    {
        $response = $this->httpRequest('DELETE', '/api/courses/'.$id,
            [
                'Accept' => 'application/json2',
            ]
        );

        self::assertSame(406, $response['status']['code']);

        self::assertArrayHasKey('x-not-acceptable', $response['headers']);
        self::assertSame(
            'accept "application/json2" is not supported, supported are application/json, application/x-www-form-urlencoded, application/xml, application/x-yaml',
            $response['headers']['x-not-acceptable'][0]
        );
    }

    public function testDeleteWithResourceNotFound()
    {
        $response = $this->httpRequest('DELETE', '/api/courses/ccd8edff-87a5-4aa9-9c77-663ccfd7cdca', [
            'Accept' => 'application/json',
        ]);

        self::assertSame(404, $response['status']['code']);

        self::assertArrayHasKey('content-type', $response['headers']);

        self::assertSame('application/json', $response['headers']['content-type'][0]);

        $data = json_decode($response['body'], true);

        self::assertEquals(
            [
                'scope' => 'resource',
                'key' => 'resource_not_found',
                'detail' => 'the wished resource does not exist',
                'reference' => 'course',
                'arguments' => [
                    'id' => 'ccd8edff-87a5-4aa9-9c77-663ccfd7cdca',
                ],
                '_type' => 'error',
            ],
            $data
        );
    }
}
