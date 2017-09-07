<?php

namespace Chubbyphp\Tests\ApiSkeleton\Unit\ApiHttp;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface as Response;
use Chubbyphp\ApiSkeleton\ApiHttp\ResponseFactory;

/**
 * @covers \Chubbyphp\ApiSkeleton\ApiHttp\ResponseFactory
 */
class ResponseFactoryTest extends TestCase
{
    public function testCreateResponse()
    {
        $factory = new ResponseFactory();

        $response = $factory->createResponse();

        self::assertInstanceOf(Response::class, $response);

        self::assertSame(200, $response->getStatusCode());
    }
}
