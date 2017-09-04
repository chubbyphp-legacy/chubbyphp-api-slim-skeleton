<?php

declare(strict_types=1);

namespace Chubbyphp\ApiSkeleton\Error;

use Chubbyphp\Translation\TranslatorInterface;
use Chubbyphp\Validation\Error\NestedErrorMessages;

class ErrorManager
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param string $type
     * @param array  $arguments
     *
     * @return Error
     */
    public function createByMissingModel(string $type, array $arguments): Error
    {
        return new Error(
            Error::SCOPE_RESOURCE,
            'missing.model',
            'the wished model does not exist',
            $type,
            $arguments
        );
    }

    /**
     * @param array  $errors
     * @param string $locale
     * @param string $scope
     * @param string $type
     *
     * @return Error
     */
    public function createByValidationErrors(array $errors, string $locale, string $scope, string $type): Error
    {
        $nestedErrorMessage = new NestedErrorMessages($errors, function (string $key, array $arguments) use ($locale) {
            return $this->translator->translate($locale, $key, $arguments);
        });

        return new Error(
            $scope,
            'validation.errors',
            'there are validation errors while validating the model',
            $type,
            $nestedErrorMessage->getMessages()
        );
    }

    /**
     * @param string $type
     * @param string $contentType
     * @param string $body
     * @return Error
     */
    public function createNotParsable(string $type, string $contentType, string $body): Error
    {
         return new Error(
            Error::SCOPE_BODY,
            'notparsable',
            'request body not parsable',
            $type,
            ['body' => $body, 'contentType' => $contentType]
         );
    }
}
