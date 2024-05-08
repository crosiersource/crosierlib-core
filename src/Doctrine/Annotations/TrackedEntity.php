<?php

namespace CrosierSource\CrosierLibCoreBundle\Doctrine\Annotations;


use Attribute;
use Doctrine\Common\Annotations\Annotation;

/**
 * Marca uma entidade para ter suas alterações logadas no cfg_entities_changes
 * @author Carlos Eduardo Pauluk
 */
#[Attribute(Attribute::TARGET_CLASS)]
class TrackedEntity extends Annotation
{

}
