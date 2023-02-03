<?php

namespace CrosierSource\CrosierLibCoreBundle\EntityHandler\Config;

use CrosierSource\CrosierLibCoreBundle\Entity\Config\EntMenuLocator;
use CrosierSource\CrosierLibCoreBundle\EntityHandler\EntityHandler;

/**
 * Class EntMenuLocatorEntityHandler
 * @package CrosierSource\CrosierLibCoreBundle\EntityHandler\Config
 * @author Carlos Eduardo Pauluk
 */
class EntMenuLocatorEntityHandler extends EntityHandler
{


    public function getEntityClass()
    {
        return EntMenuLocator::class;
    }
}