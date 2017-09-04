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

final class CourseCreateController
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
        if (null === $contentType = $this->requestManager->getContentType($request)) {
            return $this->responseManager->createResponse($request, 415);
        }

        /** @var Course $course */
        $course = $this->requestManager->getDataFromRequestBody($request, Course::class, $contentType);

        if (null === $course) {
            return $this->responseManager->createResponse(
                $request,
                400,
                $this->errorManager->createNotParsable('course', $contentType, (string) $request->getBody())
            );
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

        return $this->responseManager->createResponse($request, 201, $course);
    }
}
