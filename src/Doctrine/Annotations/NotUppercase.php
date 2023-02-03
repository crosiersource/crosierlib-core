<?php

namespace CrosierSource\CrosierLibCoreBundle\Doctrine\Annotations;


use Attribute;
use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 *
 * @author Carlos Eduardo Pauluk
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class NotUppercase extends Annotation
{

}
