<?php

namespace CrosierSource\CrosierLibCoreBundle\Repository\Security;

use CrosierSource\CrosierLibCoreBundle\Entity\Security\Group;
use CrosierSource\CrosierLibCoreBundle\Repository\FilterRepository;

/**
 * @author Carlos Eduardo Pauluk
 */
class GroupRepository extends FilterRepository
{

    public function getEntityClass(): string
    {
        return Group::class;
    }
}
