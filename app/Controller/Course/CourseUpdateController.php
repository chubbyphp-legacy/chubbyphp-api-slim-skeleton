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
use Chubbyphp\ApiSkeleton\Model\Course;

final class CourseUpdateController
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

        if (null === $contentType = $this->requestManager->getContentType($request)) {
            return $this->responseManager->createContentTypeNotSupportedResponse($request, $accept);
        }

        $id = $request->getAttribute('id');

        /** @var Course $course */
        if (null === $course = $this->repository->find($id)) {
            return $this->responseManager->createResourceNotFoundResponse($request, $accept, 'course', ['id' => $id]);
        }

        /** @var Course $course */
        if (null === $course = $this->requestManager->getDataFromRequestBody($request, $course, $contentType)) {
            return $this->responseManager->createBodyNotDeserializableResponse($request, $accept, $contentType);
        }

        if ([] !== $errors = $this->validator->validateObject($course)) {
            $locale = $this->requestManager->getAcceptLanguage($request, $this->defaultLanguage);

            return $this->responseManager->createValidationErrorResponse(
                $request,
                $accept,
                Error::SCOPE_BODY,
                'course',
                (new NestedErrorMessages($errors, function (string $key, array $arguments) use ($locale) {
                    return $this->translator->translate($locale, $key, $arguments);
                }))->getMessages()
            );
        }

        $this->repository->persist($course);

        return $this->responseManager->createResponse($request, 200, $accept, $course);
    }
}
