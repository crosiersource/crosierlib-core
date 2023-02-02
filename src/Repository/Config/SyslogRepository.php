<?php

namespace CrosierSource\CrosierLibCoreBundle\Repository\Config;

use CrosierSource\CrosierLibCoreBundle\Entity\Logs\Syslog;
use CrosierSource\CrosierLibCoreBundle\Repository\FilterRepository;

/**
 * @author Carlos Eduardo Pauluk
 */
class SyslogRepository extends FilterRepository
{

    public function getEntityClass(): string
    {
        return Syslog::class;
    }
}
