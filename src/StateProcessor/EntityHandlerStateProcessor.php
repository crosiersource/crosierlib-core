<?php

namespace CrosierSource\CrosierLibCoreBundle\StateProcessor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use CrosierSource\CrosierLibCoreBundle\Doctrine\Annotations\EntityHandler;
use FilesystemIterator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

readonly class EntityHandlerStateProcessor // implements ProviderInterface, ServiceSubscriberInterface
{

	public function __construct(
		private ContainerInterface $container,
		#[Autowire(service: 'api_platform.doctrine.orm.state.item_provider')]
		private ProviderInterface  $itemProvider,
	)
	{
	}

	/**
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
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
		$dir = __DIR__ . '/../EntityHandler';
		$classes = [];
		$iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS));
		foreach ($iterator as $file) {
			if ($file->isDir()) continue;
			if (in_array($file->getFilename(), ['EntityHandler.php', 'EntityHandlerInterface.php'])) continue;
			if ($file->getExtension() === 'php') {
				$name = 'CrosierSource\\CrosierLibCoreBundle\\EntityHandler\\' .
					str_replace([$dir . '/', '.php', '/'], ['', '', '\\'], $file->getPathname());
				$classes[] = $name;
			}
		}
		return $classes;
	}

	public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
	{
		$entity = $this->itemProvider->provide($operation, $uriVariables, $context);

		return $entity;
	}
}
