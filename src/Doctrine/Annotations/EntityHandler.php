<?php

namespace CrosierSource\CrosierLibCoreBundle\Doctrine\Annotations;


use Attribute;


#[Attribute(Attribute::TARGET_CLASS)]
class EntityHandler
{

	private string $entityHandlerClass;

	public function getEntityHandlerClass(): string
	{
		return $this->entityHandlerClass;
	}

	public function setEntityHandlerClass(string $entityHandlerClass): void
	{
		$this->entityHandlerClass = $entityHandlerClass;
	}


}
