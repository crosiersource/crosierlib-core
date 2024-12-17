<?php

namespace CrosierSource\CrosierLibCoreBundle\EntityHandler\Config;

use CrosierSource\CrosierLibCoreBundle\Entity\Config\MenuItem;
use CrosierSource\CrosierLibCoreBundle\EntityHandler\EntityHandler;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;

/**
 * @author Carlos Eduardo Pauluk
 */
#[AsDoctrineListener(event: Events::prePersist, priority: 0)]
#[AsDoctrineListener(event: Events::postPersist, priority: 0)]
#[AsDoctrineListener(event: Events::preUpdate, priority: 0)]
#[AsDoctrineListener(event: Events::postUpdate, priority: 0)]
#[AsDoctrineListener(event: Events::preRemove, priority: 0)]
#[AsDoctrineListener(event: Events::postRemove, priority: 0)]
class MenuItemEntityHandler extends EntityHandler
{

	public function getEntityClass(): string
	{
		return MenuItem::class;
	}

}
