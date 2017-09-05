<?php

declare(strict_types=1);

namespace Chubbyphp\ApiSkeleton\Controller;

use Chubbyphp\ApiHttp\Manager\RequestManagerInterface;
use Chubbyphp\ApiHttp\Manager\ResponseManagerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Chubbyphp\ApiSkeleton\Search\Index;

final class IndexController
{
    /**
     * @var RequestManagerInterface
     */
    private $requestManager;

    /**
     * @var ResponseManagerInterface
     */
    private $responseManager;

    /**
     * @param RequestManagerInterface  $requestManager
     * @param ResponseManagerInterface $responseManager
     */
    public function __construct(RequestManagerInterface $requestManager, ResponseManagerInterface $responseManager)
    {
        $this->requestManager = $requestManager;
        $this->responseManager = $responseManager;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request)
    {
        if (null === $accept = $this->requestManager->getAccept($request)) {
            return $this->responseManager->createAcceptNotSupportedResponse($request);
        }

        return $this->responseManager->createResponse($request, 200, $accept, new Index());
    }
}
