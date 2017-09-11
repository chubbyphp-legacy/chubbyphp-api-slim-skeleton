<?php

declare(strict_types=1);

namespace Chubbyphp\ApiSkeleton\Serialization;

use Chubbyphp\Serialization\Link\LinkGeneratorInterface;
use Chubbyphp\Serialization\Mapping\FieldMappingInterface;
use Chubbyphp\Serialization\Mapping\LinkMapping;
use Chubbyphp\Serialization\Mapping\LinkMappingInterface;
use Chubbyphp\Serialization\Mapping\ObjectMappingInterface;
use Chubbyphp\Serialization\Serializer\Link\CallbackLinkSerializer;
use Chubbyphp\ApiSkeleton\Search\Index;
use Psr\Http\Message\ServerRequestInterface as Request;

final class IndexMapping implements ObjectMappingInterface
{
    /**
     * @var LinkGeneratorInterface
     */
    private $linkGenerator;

    /**
     * @param LinkGeneratorInterface $linkGenerator
     */
    public function __construct(LinkGeneratorInterface $linkGenerator)
    {
        $this->linkGenerator = $linkGenerator;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return Index::class;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'index';
    }

    /**
     * @return FieldMappingInterface[]
     */
    public function getFieldMappings(): array
    {
        return [];
    }

    /**
     * @return FieldMappingInterface[]
     */
    public function getEmbeddedFieldMappings(): array
    {
        return [];
    }

    /**
     * @return LinkMappingInterface[]
     */
    public function getLinkMappings(): array
    {
        return [
            new LinkMapping('self', new CallbackLinkSerializer(function (Request $request) {
                return $this->linkGenerator->generateLink($request, 'index');
            })),
            new LinkMapping('courses', new CallbackLinkSerializer(function (Request $request) {
                return $this->linkGenerator->generateLink($request, 'course_search');
            })),
        ];
    }
}
