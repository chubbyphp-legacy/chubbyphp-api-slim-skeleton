<?php

declare(strict_types=1);

namespace Chubbyphp\ApiSkeleton\Controller\Course;

use Chubbyphp\ApiHttp\Error\Error;
use Chubbyphp\ApiHttp\Manager\RequestManagerInterface;
use Chubbyphp\ApiHttp\Manager\ResponseManagerInterface;
use Chubbyphp\Model\RepositoryInterface;
use Chubbyphp\Translation\TranslatorInterface;
use Chubbyphp\Validation\Error\NestedErrorMessages;
use Chubbyphp\Validation\ValidatorInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Chubbyphp\ApiSkeleton\Search\CourseSearch;

final class CourseSearchController
{
    /**
     * @var string
     */
    private $defaultLanguage;

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
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @param string                   $defaultLanguage
     * @param RepositoryInterface      $repository
     * @param RequestManagerInterface  $requestManager
     * @param ResponseManagerInterface $responseManager
     * @param TranslatorInterface      $translator
     * @param ValidatorInterface       $validator
     */
    public function __construct(
        string $defaultLanguage,
        RepositoryInterface $repository,
        RequestManagerInterface $requestManager,
        ResponseManagerInterface $responseManager,
        TranslatorInterface $translator,
        ValidatorInterface $validator
    ) {
        $this->defaultLanguage = $defaultLanguage;
        $this->repository = $repository;
        $this->requestManager = $requestManager;
        $this->responseManager = $responseManager;
        $this->translator = $translator;
        $this->validator = $validator;
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

        /** @var CourseSearch $courseSearch */
        $courseSearch = $this->requestManager->getDataFromRequestQuery($request, CourseSearch::class);

        if ([] !== $errors = $this->validator->validateObject($courseSearch)) {
            $locale = $this->requestManager->getAcceptLanguage($request, $this->defaultLanguage);

            return $this->responseManager->createValidationErrorResponse(
                $request,
                $accept,
                Error::SCOPE_QUERY,
                'course-search',
                (new NestedErrorMessages($errors, function (string $key, array $arguments) use ($locale) {
                    return $this->translator->translate($locale, $key, $arguments);
                }))->getMessages()
            );
        }

        /** @var CourseSearch $courseSearch */
        $courseSearch = $this->repository->search($courseSearch);

        return $this->responseManager->createResponse($request, 200, $accept, $courseSearch);
    }
}
