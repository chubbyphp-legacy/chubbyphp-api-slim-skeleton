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
use Chubbyphp\ApiSkeleton\Search\CourseSearch;

final class CourseSearchController
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
        /** @var CourseSearch $courseSearch */
        $courseSearch = $this->requestManager->getDataFromRequestQuery($request, CourseSearch::class);

        if ([] !== $errors = $this->validator->validateObject($courseSearch)) {
            return $this->responseManager->createResponse(
                $request,
                400,
                $this->errorManager->createByValidationErrors(
                    $errors,
                    $this->requestManager->getAcceptLanguage($request, $this->defaultLanguage),
                    Error::SCOPE_QUERY,
                    Course::class
                )
            );
        }

        /** @var CourseSearch $courseSearch */
        $courseSearch = $this->repository->search($courseSearch);

        return $this->responseManager->createResponse($request, 200, $courseSearch);
    }
}
