<?php

namespace CrosierSource\CrosierLibCoreBundle\Doctrine\Annotations;


use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class EntityHandler extends Annotation
{
    /**
     * @var string
     */
    public string $entityHandlerClass;

}
