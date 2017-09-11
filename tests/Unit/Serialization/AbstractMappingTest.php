<?php

namespace Chubbyphp\Tests\ApiSkeleton\Unit\Serialization;

use Chubbyphp\Serialization\Link\LinkGeneratorInterface;
use Chubbyphp\Serialization\Link\LinkInterface;
use Chubbyphp\Serialization\Mapping\LinkMappingInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Route;

/**
 * @coversNothing
 */
abstract class AbstractMappingTest extends TestCase
{
    /**
     * @param Request $request
     * @param array   $linkMappings
     * @param array   $nameAndHrefs
     */
    protected function assertLinks(array $linkMappings, Request $request, $object, $fields, array $nameAndHrefs)
    {
        foreach ($linkMappings as $i => $linkMapping) {
            if (!isset($nameAndHrefs[$i])) {
                throw new \InvalidArgumentException(sprintf('Missing mapping for index %d', $i));
            }

            $this->assertLink(
                $linkMapping,
                $request,
                $object,
                $fields,
                $nameAndHrefs[$i]['name'],
                $nameAndHrefs[$i]['href']
            );
        }
    }

    /**
     * @param LinkMappingInterface $linkMapping
     * @param Request              $request
     * @param object               $object
     * @param array                $fields
     * @param string               $name
     * @param string               $href
     */
    protected function assertLink(
        LinkMappingInterface $linkMapping,
        Request $request,
        $object,
        array $fields,
        string $name,
        string $href = null
    ) {
        self::assertSame($name, $linkMapping->getName());
        if (null !== $href) {
            self::assertEquals(
                $this->getLink([
                    'href' => $href,
                    'method' => 'GET',
                ])->jsonSerialize(),
                $linkMapping->getLinkSerializer()->serializeLink('', $request, $object, $fields)->jsonSerialize()
            );
        } else {
            self::assertEquals(
                [],
                $linkMapping->getLinkSerializer()->serializeLink('', $request, $object, $fields)->jsonSerialize()
            );
        }
    }

    /**
     * @return LinkGeneratorInterface
     */
    protected function getLinkGenerator(): LinkGeneratorInterface
    {
        /** @var LinkGeneratorInterface|\PHPUnit_Framework_MockObject_MockObject $generator */
        $generator = $this->getMockBuilder(LinkGeneratorInterface::class)
            ->setMethods(['generateLink'])
            ->getMockForAbstractClass();

        $generator->expects(self::any())->method('generateLink')->willReturnCallback(
            function (Request $request, string $routeName, array $data = [], array $queryParams = []) {
                return $this->getLink([
                    'href' => json_encode(['routeName' => $routeName, 'data' => $data, 'queryParams' => $queryParams]),
                    'method' => 'GET',
                ]);
            }
        );

        return $generator;
    }

    /**
     * @param array $attributes
     *
     * @return Request
     */
    protected function getRequest(array $attributes = []): Request
    {
        /** @var Request|\PHPUnit_Framework_MockObject_MockObject $request */
        $request = $this->getMockBuilder(Request::class)->setMethods(['getAttribute'])->getMockForAbstractClass();

        $request->expects(self::any())->method('getAttribute')->willReturnCallback(
            function (string $name) use ($attributes) {
                if (isset($attributes[$name])) {
                    return $attributes[$name];
                }

                return null;
            }
        );

        return $request;
    }

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return Route
     */
    protected function getRoute(string $name, array $arguments): Route
    {
        /** @var Route|\PHPUnit_Framework_MockObject_MockObject $route */
        $route = $this->getMockBuilder(Route::class)
            ->setMethods(['getName', 'getArguments'])
            ->disableOriginalConstructor()
            ->getMock();

        $route->expects(self::any())->method('getName')->willReturn($name);
        $route->expects(self::any())->method('getArguments')->willReturn($arguments);

        return $route;
    }

    /**
     * @param array $data
     *
     * @return LinkInterface
     */
    protected function getLink(array $data): LinkInterface
    {
        /** @var LinkInterface|\PHPUnit_Framework_MockObject_MockObject $link */
        $link = $this->getMockBuilder(LinkInterface::class)->setMethods(['jsonSerialize'])->getMockForAbstractClass();

        $link->expects(self::any())->method('jsonSerialize')->willReturn($data);

        return $link;
    }
}
