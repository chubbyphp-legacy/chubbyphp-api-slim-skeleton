<?php

declare(strict_types=1);

namespace Chubbyphp\ApiSkeleton\Controller\Course;

use Chubbyphp\ApiHttp\Manager\RequestManagerInterface;
use Chubbyphp\ApiHttp\Manager\ResponseManagerInterface;
use Chubbyphp\Model\RepositoryInterface;
use Chubbyphp\Validation\ValidatorInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Chubbyphp\ApiSkeleton\Error\Error;
use Chubbyphp\ApiSkeleton\Error\ErrorManager;
use Chubbyphp\ApiSkeleton\Model\Course;

final class CourseUpdateController
{
    /**
     * @var string
     */
    private $defaultLanguage;

    /**
     * @var ErrorManager
     */
    private $errorManager;

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
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @param string                   $defaultLanguage
     * @param ErrorManager             $errorManager
     * @param RepositoryInterface      $repository
     * @param RequestManagerInterface  $requestManager
     * @param ResponseManagerInterface $responseManager
     * @param ValidatorInterface       $validator
     */
    public function __construct(
        string $defaultLanguage,
        ErrorManager $errorManager,
        RepositoryInterface $repository,
        RequestManagerInterface $requestManager,
        ResponseManagerInterface $responseManager,
        ValidatorInterface $validator
    ) {
        $this->defaultLanguage = $defaultLanguage;
        $this->errorManager = $errorManager;
        $this->repository = $repository;
        $this->requestManager = $requestManager;
        $this->responseManager = $responseManager;
        $this->validator = $validator;
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

        /** @var Course $course */
        $course = $this->requestManager->getDataFromRequestBody($request, $course);

        if (null === $course) {
            return $this->responseManager->createResponse($request, 415);
        }

        if ([] !== $errors = $this->validator->validateObject($course)) {
            return $this->responseManager->createResponse(
                $request,
                422,
                $this->errorManager->createByValidationErrors(
                    $errors,
                    $this->requestManager->getAcceptLanguage($request, $this->defaultLanguage),
                    Error::SCOPE_BODY,
                    'course'
                )
            );
        }

        $this->repository->persist($course);

        return $this->responseManager->createResponse($request, 200, $course);
    }
}
