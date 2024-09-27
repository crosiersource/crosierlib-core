<?php

namespace CrosierSource\CrosierLibCoreBundle\EntityHandler\Security;

use CrosierSource\CrosierLibCoreBundle\Entity\Security\Group;
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
class GroupEntityHandler extends EntityHandler
{

	public function getEntityClass(): string
	{
		return Group::class;
	}
}
