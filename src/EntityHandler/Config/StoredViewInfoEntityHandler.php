<?php

namespace CrosierSource\CrosierLibCoreBundle\EntityHandler\Config;

use CrosierSource\CrosierLibCoreBundle\Entity\Config\StoredViewInfo;
use CrosierSource\CrosierLibCoreBundle\EntityHandler\EntityHandler;

/**
 * Class StoredViewInfoEntityHandler
 *
 * @package CrosierSource\CrosierLibCoreBundle\EntityHandler\Config
 * @author Carlos Eduardo Pauluk
 */
class StoredViewInfoEntityHandler extends EntityHandler
{

    public function getEntityClass()
    {
        return StoredViewInfo::class;
    }


}