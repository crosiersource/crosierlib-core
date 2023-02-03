<?php

namespace CrosierSource\CrosierLibCoreBundle\EntityHandler\Config;

use CrosierSource\CrosierLibCoreBundle\Entity\Config\App;
use CrosierSource\CrosierLibCoreBundle\EntityHandler\EntityHandler;

/**
 * Class AppEntityHandler
 * @package App\EntityHandler\Config
 *
 * @author Carlos Eduardo Pauluk
 */
class AppEntityHandler extends EntityHandler
{

    public function getEntityClass()
    {
        return App::class;
    }
}