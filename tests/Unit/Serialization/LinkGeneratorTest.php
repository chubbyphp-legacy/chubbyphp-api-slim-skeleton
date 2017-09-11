<?php

namespace Chubbyphp\Tests\ApiSkeleton\Unit\Serialization;

use Chubbyphp\Serialization\Link\Link;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouterInterface;
use Slim\Route;
use Chubbyphp\ApiSkeleton\Serialization\LinkGenerator;

/**
 * @covers \Chubbyphp\ApiSkeleton\Serialization\LinkGenerator
 */
class LinkGeneratorTest extends TestCase
{
    public function testGenerateLink()
    {
        $linkGenerator = new LinkGenerator($this->getRouter());

        self::assertEquals(
            new Link('{"name":"name","data":{"id":"id1"},"queryParams":{"tracking":"1"}}', 'GET'),
            $linkGenerator->generateLink(
                $this->getRequest(),
                'name',
                ['id' => 'id1'],
                ['tracking' => '1']
            )
        );
    }

    /**
     * @return RouterInterface
     */
    private function getRouter(): RouterInterface
    {
        /** @var RouterInterface|\PHPUnit_Framework_MockObject_MockObject $router */
        $router = $this->getMockBuilder(RouterInterface::class)
            ->setMethods(['getNamedRoute', 'pathFor'])
            ->getMockForAbstractClass();

        $router->expects(self::any())->method('getNamedRoute')->willReturnCallback(
            function ($routeName) {
                return $this->getRoute(['GET']);
            }
        );

        $router->expects(self::any())->method('pathFor')->willReturnCallback(
            function ($name, array $data = [], array $queryParams = []) {
                return json_encode(['name' => $name, 'data' => $data, 'queryParams' => $queryParams]);
            }
        );

        return $router;
    }

    /**
     * @return Request
     */
    private function getRequest(): Request
    {
        /** @var Request|\PHPUnit_Framework_MockObject_MockObject $request */
        $request = $this->getMockBuilder(Request::class)->setMethods([])->getMockForAbstractClass();

        return $request;
    }

    /**
     * @param array $methods
     *
     * @return Route
     */
    private function getRoute(array $methods): Route
    {
        /** @var Route|\PHPUnit_Framework_MockObject_MockObject $route */
        $route = $this->getMockBuilder(Route::class)
            ->setMethods(['getMethods'])
            ->disableOriginalConstructor()
            ->getMock();

        $route->expects(self::any())->method('getMethods')->willReturn($methods);

        return $route;
    }
}
