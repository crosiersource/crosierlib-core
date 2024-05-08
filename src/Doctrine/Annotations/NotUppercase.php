<?php

namespace CrosierSource\CrosierLibCoreBundle\Doctrine\Annotations;


use Attribute;


/**
 * Marca um campo que não deve ser automaticamente convertido para uppercase.
 * @author Carlos Eduardo Pauluk
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class NotUppercase
{

}
