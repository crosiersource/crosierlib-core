<?php

namespace CrosierSource\CrosierLibCoreBundle\Repository\Config;

use CrosierSource\CrosierLibCoreBundle\Entity\Logs\EntityChange;
use CrosierSource\CrosierLibCoreBundle\Repository\FilterRepository;

/**
 * Repository para a entidade EntityChange.
 *
 * @author Carlos Eduardo Pauluk
 *
 */
class EntityChangeRepository extends FilterRepository
{

    public function getEntityClass(): string
    {
        return EntityChange::class;
    }


}

