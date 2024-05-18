<?php

namespace CrosierSource\CrosierLibCoreBundle\Repository\Security;

use CrosierSource\CrosierLibCoreBundle\Entity\Security\Role;
use CrosierSource\CrosierLibCoreBundle\Repository\FilterRepository;

/**
 * @author Carlos Eduardo Pauluk
 */
class RoleRepository extends FilterRepository
{

	public function getEntityClass(): string
	{
		return Role::class;
	}
}
