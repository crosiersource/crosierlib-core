<?php

namespace CrosierSource\CrosierLibCoreBundle\Doctrine\Annotations;


use Attribute;
use Doctrine\Common\Annotations\Annotation;

/**
 * Marca um atributo de uma entidade do tipo \DateTime para que no TrackedEntity seja tratado apenas a parte da data.
 *
 * @Annotation
 * @Target("PROPERTY")
 *
 * @author Carlos Eduardo Pauluk
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class TrackDateOnly extends Annotation
{

}
