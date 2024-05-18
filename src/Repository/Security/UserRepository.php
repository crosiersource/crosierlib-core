<?php

namespace CrosierSource\CrosierLibCoreBundle\Repository\Security;

use CrosierSource\CrosierLibCoreBundle\Entity\Security\User;
use CrosierSource\CrosierLibCoreBundle\Exception\ViewException;
use CrosierSource\CrosierLibCoreBundle\Repository\FilterRepository;

class UserRepository extends FilterRepository
{

	public function getEntityClass(): string
	{
		return User::class;
	}

	/**
	 * @throws ViewException
	 */
	public function getUsersByEmail(string $email): array
	{
		return $this->findAllByFiltersSimpl([['email', 'LIKE', '%' . $email . '%']], ['username' => 'ASC']);
	}

}
