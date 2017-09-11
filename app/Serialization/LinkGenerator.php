<?php

declare(strict_types=1);

namespace Chubbyphp\ApiSkeleton\Serialization;

use Chubbyphp\Serialization\Link\Link;
use Chubbyphp\Serialization\Link\LinkGeneratorInterface;
use Chubbyphp\Serialization\Link\LinkInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouterInterface;
use Slim\Route;

final class LinkGenerator implements LinkGeneratorInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param string $routeName
     * @param array  $data
     * @param array  $queryParams
     *
     * @return LinkInterface
     */
    public function generateLink(
        Request $request,
        string $routeName,
        array $data = [],
        array $queryParams = []
    ): LinkInterface {
        /** @var Route $route */
        $route = $this->router->getNamedRoute($routeName);

        return new Link(
            $this->router->pathFor($routeName, $data, $queryParams),
            implode('|', $route->getMethods())
        );
    }
}
