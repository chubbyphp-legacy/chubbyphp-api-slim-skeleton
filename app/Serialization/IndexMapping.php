<?php

declare(strict_types=1);

namespace Chubbyphp\ApiSkeleton\Serialization;

use Chubbyphp\Serialization\Mapping\FieldMappingInterface;
use Chubbyphp\Serialization\Mapping\LinkMapping;
use Chubbyphp\Serialization\Mapping\LinkMappingInterface;
use Chubbyphp\Serialization\Mapping\ObjectMappingInterface;
use Chubbyphp\Serialization\Serializer\Link\CallbackLinkSerializer;
use Chubbyphp\ApiSkeleton\Search\Index;

final class IndexMapping implements ObjectMappingInterface
{
    /**
     * @var LinkGenerator
     */
    private $linkGenerator;

    /**
     * @param LinkGenerator $linkGenerator
     */
    public function __construct(LinkGenerator $linkGenerator)
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
            new LinkMapping('self', new CallbackLinkSerializer(function () {
                return $this->linkGenerator->generateLink('index');
            })),
            new LinkMapping('courses', new CallbackLinkSerializer(function () {
                return $this->linkGenerator->generateLink('course_search');
            })),
        ];
    }
}
