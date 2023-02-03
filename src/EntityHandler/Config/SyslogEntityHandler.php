<?php

namespace CrosierSource\CrosierLibCoreBundle\EntityHandler\Config;

use CrosierSource\CrosierLibCoreBundle\Entity\Logs\Syslog;
use CrosierSource\CrosierLibCoreBundle\EntityHandler\EntityHandler;

/**
 * @author Carlos Eduardo Pauluk
 */
class SyslogEntityHandler extends EntityHandler
{

    public function getEntityClass()
    {
        return Syslog::class;
    }


}