<?php

namespace CrosierSource\CrosierLibCoreBundle\EntityHandler\Config;

use CrosierSource\CrosierLibCoreBundle\Entity\Config\Estabelecimento;
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
class EstabelecimentoEntityHandler extends EntityHandler
{

	public function getEntityClass(): string
	{
		return Estabelecimento::class;
	}

}
