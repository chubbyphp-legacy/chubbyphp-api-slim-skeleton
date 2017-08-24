<?php

declare(strict_types=1);

namespace Chubbyphp\ApiSkeleton\Controller\Course;

use Chubbyphp\ApiHttp\Manager\ResponseManagerInterface;
use Chubbyphp\Model\RepositoryInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Chubbyphp\ApiSkeleton\Error\ErrorManager;
use Chubbyphp\ApiSkeleton\Model\Course;

final class CourseReadController
{
    /**
     * @var ErrorManager
     */
    private $errorManager;

    /**
     * @var RepositoryInterface
     */
    private $repository;

    /**
     * @var ResponseManagerInterface
     */
    private $responseManager;

    /**
     * @param ErrorManager             $errorManager
     * @param RepositoryInterface      $repository
     * @param ResponseManagerInterface $responseManager
     */
    public function __construct(
        ErrorManager $errorManager,
        RepositoryInterface $repository,
        ResponseManagerInterface $responseManager
    ) {
        $this->errorManager = $errorManager;
        $this->repository = $repository;
        $this->responseManager = $responseManager;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $id = $request->getAttribute('id');

        /** @var Course $course */
        $course = $this->repository->find($id);

        if (null === $course) {
            return $this->responseManager->createResponse(
                $request,
                404,
                $this->errorManager->createByMissingModel('course', ['id' => $id])
            );
        }

        return $this->responseManager->createResponse($request, 200, $course);
    }
}
