<?php

namespace CrosierSource\CrosierLibCoreBundle\Repository\Config;


use CrosierSource\CrosierLibCoreBundle\Entity\Config\MenuItem;
use CrosierSource\CrosierLibCoreBundle\Repository\FilterRepository;

/**
 * @author Carlos Eduardo Pauluk
 */
class MenuItemRepository extends FilterRepository
{
	/**
	 * @return string
	 */
	public function getEntityClass(): string
	{
		return MenuItem::class;
	}
}
