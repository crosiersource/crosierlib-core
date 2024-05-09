<?php

namespace CrosierSource\CrosierLibCoreBundle\StateProcessor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use CrosierSource\CrosierLibCoreBundle\Doctrine\Annotations\EntityHandler;
use CrosierSource\CrosierLibCoreBundle\EntityHandler\Security\GroupEntityHandler;
use Psr\Container\ContainerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class EntityHandlerStateProcessor implements ProcessorInterface, ServiceSubscriberInterface
{

	public function __construct(private ContainerInterface $container)
	{
		// $container->get(GroupEntityHandler::class);
		$oi = 1;
	}

	public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
	{
		$eh = null;
		$reflectionClass = new \ReflectionClass(get_class($data));
		foreach ($reflectionClass->getAttributes() as $att) {
			if ($att->getName() === EntityHandler::class) {
				foreach ($att->getArguments() as $arg) {
					$eh = $this->container->get($arg);
				}
			}
		}
		$eh->save($data);
	}

	public static function getSubscribedServices(): array
	{
//		$dir = __DIR__ . '/../../EntityHandler';
//		$classes = [];
//		$iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS));
//
//		foreach ($iterator as $file) {
//			if ($file->isDir()) continue;
//			if ($file->getExtension() === 'php') {
//				$name = 'CrosierSource\\CrosierLibCoreBundle\\EntityHandler\\' .
//					str_replace([$dir . '/', '.php', '/'], ['', '', '\\'], $file->getPathname());
//				$classes[] = $name;
//			}
//		}
//		return $classes;
		return [GroupEntityHandler::class];
	}

}
