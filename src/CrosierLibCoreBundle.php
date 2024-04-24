<?php

namespace CrosierSource\CrosierLibCoreBundle;

use CrosierSource\CrosierLibCoreBundle\DependencyInjection\CrosierLibCoreExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CrosierLibCoreBundle extends Bundle
{

	public function getPath(): string
	{
		return \dirname(__DIR__);
	}

	public function getContainerExtension(): ?\Symfony\Component\DependencyInjection\Extension\ExtensionInterface
	{
		if (null === $this->extension) {
			$this->extension = new CrosierLibCoreExtension();
		}
		return $this->extension;

	}


}
