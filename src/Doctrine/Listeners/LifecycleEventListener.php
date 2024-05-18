<?php

namespace CrosierSource\CrosierLibCoreBundle\Doctrine\Listeners;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

#[AsDoctrineListener(event: Events::preUpdate, priority: 0, connection: 'default')]
#[AsDoctrineListener(event: Events::prePersist, priority: 0, connection: 'default')]
class LifecycleEventListener
{

	public function preUpdate(PreUpdateEventArgs $args): void
	{
		$entity = $args->getObject();
		$entityManager = $args->getObjectManager();
	}

	public function prePersist(PrePersistEventArgs $args): void
	{
		$entity = $args->getObject();
		$entityManager = $args->getObjectManager();
	}
}
