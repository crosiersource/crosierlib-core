<?php

namespace CrosierSource\CrosierLibCoreBundle\Doctrine\Annotations;


use Attribute;


#[Attribute(Attribute::TARGET_CLASS)]
class EntityHandler
{

	private string $entityHandlerClass;

	public function __construct(string $entityHandlerClass)
	{
		$this->entityHandlerClass = $entityHandlerClass;
	}

	public function getEntityHandlerClass(): string
	{
		return $this->entityHandlerClass;
	}

	public function setEntityHandlerClass(string $entityHandlerClass): void
	{
		$this->entityHandlerClass = $entityHandlerClass;
	}


}
