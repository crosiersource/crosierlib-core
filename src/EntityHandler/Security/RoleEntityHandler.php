<?php

namespace CrosierSource\CrosierLibCoreBundle\EntityHandler\Security;

use CrosierSource\CrosierLibCoreBundle\Entity\Security\Role;
use CrosierSource\CrosierLibCoreBundle\EntityHandler\EntityHandler;

/**
 * Class RoleEntityHandler
 * @package App\EntityHandler\Security
 * @author Carlos Eduardo Pauluk
 */
class RoleEntityHandler extends EntityHandler
{

    public function getEntityClass()
    {
        return Role::class;
    }
}