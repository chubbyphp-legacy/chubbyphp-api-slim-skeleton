<?php

declare(strict_types=1);

namespace Chubbyphp\ApiSkeleton\Controller;

use Chubbyphp\ApiHttp\Manager\ResponseManagerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Chubbyphp\ApiSkeleton\Search\Index;

final class IndexController
{
    /**
     * @var ResponseManagerInterface
     */
    private $responseManager;

    /**
     * @param ResponseManagerInterface $responseManager
     */
    public function __construct(ResponseManagerInterface $responseManager)
    {
        $this->responseManager = $responseManager;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request)
    {
        return $this->responseManager->createResponse($request, 200, new Index());
    }
}
