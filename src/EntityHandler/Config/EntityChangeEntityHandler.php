<?php

namespace CrosierSource\CrosierLibCoreBundle\EntityHandler\Config;

use CrosierSource\CrosierLibCoreBundle\EntityHandler\EntityHandler;

/**
 * @author Carlos Eduardo Pauluk
 */
class EntityChangeEntityHandler extends EntityHandler
{

    public function getEntityClass()
    {
        return EntityChange::class;
    }


}