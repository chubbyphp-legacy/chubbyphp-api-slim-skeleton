<?php

declare(strict_types=1);

namespace Chubbyphp\ApiSkeleton\Controller\Course;

use Chubbyphp\ApiHttp\Manager\RequestManagerInterface;
use Chubbyphp\ApiHttp\Manager\ResponseManagerInterface;
use Chubbyphp\Model\RepositoryInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Chubbyphp\ApiSkeleton\Model\Course;

final class CourseReadController
{
    /**
     * @var RepositoryInterface
     */
    private $repository;

    /**
     * @var RequestManagerInterface
     */
    private $requestManager;

    /**
     * @var ResponseManagerInterface
     */
    private $responseManager;

    /**
     * @param RepositoryInterface      $repository
     * @param RequestManagerInterface  $requestManager
     * @param ResponseManagerInterface $responseManager
     */
    public function __construct(
        RepositoryInterface $repository,
        RequestManagerInterface $requestManager,
        ResponseManagerInterface $responseManager
    ) {
        $this->repository = $repository;
        $this->requestManager = $requestManager;
        $this->responseManager = $responseManager;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        if (null === $accept = $this->requestManager->getAccept($request)) {
            return $this->responseManager->createAcceptNotSupportedResponse($request);
        }

        $id = $request->getAttribute('id');

        /** @var Course $course */
        if (null === $course = $this->repository->find($id)) {
            return $this->responseManager->createResourceNotFoundResponse($request, $accept, 'course', ['id' => $id]);
        }

        return $this->responseManager->createResponse($request, 200, $accept, $course);
    }
}
