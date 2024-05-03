<?php

namespace CrosierSource\CrosierLibCoreBundle\EntityHandler\Security;

use CrosierSource\CrosierLibCoreBundle\Entity\Security\Group;
use CrosierSource\CrosierLibCoreBundle\EntityHandler\EntityHandler;

/**
 * Class GroupEntityHandler
 * @package App\EntityHandler\Security
 * @author Carlos Eduardo Pauluk
 */
class GroupEntityHandler extends EntityHandler
{

    public function getEntityClass()
    {
        return Group::class;
    }
}
