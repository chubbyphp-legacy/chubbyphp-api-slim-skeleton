<?php

namespace Chubbyphp\Tests\ApiSkeleton\Integration\Controller;

use Chubbyphp\Tests\ApiSkeleton\Integration\AbstractIntegrationTest;

class IndexControllerTest extends AbstractIntegrationTest
{
    public function testIndex()
    {
        $response = $this->httpRequest('GET', '/api', ['Accept' => 'application/json']);

        self::assertSame(200, $response['status']['code']);

        self::assertArrayHasKey('content-type', $response['headers']);

        self::assertSame('application/json', $response['headers']['content-type'][0]);

        $data = json_decode($response['body'], true);

        self::assertEquals([
            '_links' => [
                'self' => [
                    'href' => '/api',
                    'method' => 'GET',
                ],
                'courses' => [
                    'href' => '/api/courses',
                    'method' => 'GET',
                ],
            ],
            '_type' => 'index',
        ], $data);
    }
}
