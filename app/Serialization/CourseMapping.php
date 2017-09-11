<?php

declare(strict_types=1);

namespace Chubbyphp\ApiSkeleton\Serialization;

use Chubbyphp\Serialization\Accessor\MethodAccessor;
use Chubbyphp\Serialization\Link\LinkGeneratorInterface;
use Chubbyphp\Serialization\Mapping\FieldMapping;
use Chubbyphp\Serialization\Mapping\FieldMappingInterface;
use Chubbyphp\Serialization\Mapping\LinkMapping;
use Chubbyphp\Serialization\Mapping\LinkMappingInterface;
use Chubbyphp\Serialization\Mapping\ObjectMappingInterface;
use Chubbyphp\Serialization\Serializer\Field\CollectionFieldSerializer;
use Chubbyphp\Serialization\Serializer\Link\CallbackLinkSerializer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Chubbyphp\ApiSkeleton\Model\Course;

final class CourseMapping implements ObjectMappingInterface
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
        return Course::class;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'course';
    }

    /**
     * @return FieldMappingInterface[]
     */
    public function getFieldMappings(): array
    {
        return [
            new FieldMapping('id'),
            new FieldMapping('name'),
            new FieldMapping('level'),
            new FieldMapping('progress'),
            new FieldMapping('active'),
            new FieldMapping('documents', new CollectionFieldSerializer(new MethodAccessor('getDocuments'))),
        ];
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
            new LinkMapping('read', new CallbackLinkSerializer(
                function (Request $request, Course $course) {
                    return $this->linkGenerator->generateLink($request, 'course_read', ['id' => $course->getId()]);
                }
            )),
            new LinkMapping('update', new CallbackLinkSerializer(
                function (Request $request, Course $course) {
                    return $this->linkGenerator->generateLink($request, 'course_update', ['id' => $course->getId()]);
                }
            )),
            new LinkMapping('delete', new CallbackLinkSerializer(
                function (Request $request, Course $course) {
                    return $this->linkGenerator->generateLink($request, 'course_delete', ['id' => $course->getId()]);
                }
            )),
        ];
    }
}
