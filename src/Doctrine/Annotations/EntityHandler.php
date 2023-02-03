<?php

namespace CrosierSource\CrosierLibCoreBundle\Doctrine\Annotations;


use Attribute;
use Doctrine\Common\Annotations\Annotation;
use Doctrine\ORM\Mapping\MappingAttribute;

/**
 * @Annotation
 * @Target("CLASS")
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class EntityHandler implements MappingAttribute
{
    public string $entityHandlerClass;

    public function __construct(?string $entityHandlerClass = null)
    {
        $this->entityHandlerClass = $entityHandlerClass;
    }

}
