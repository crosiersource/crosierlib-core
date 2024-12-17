<?php

namespace CrosierSource\CrosierLibCoreBundle\Business\Config;

use CrosierSource\CrosierLibCoreBundle\Entity\Config\MenuItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Yaml\Yaml;

class MenuLoader
{

	public function __construct(
		#[Autowire()]
		private EntityManagerInterface $doctrine,
	)
	{
		// ...
	}

	public function load(): void
	{
		$filePath = __DIR__ . '/../../../menu/menu.yaml';
		$menuData = Yaml::parseFile($filePath)['menu_items'] ?? [];
		$this->truncateTable();
		$this->parseMenuItems($menuData);
	}

	private function parseMenuItems(array $items, ?MenuItem $pai = null): void
	{
		$ordem = 1;
		foreach ($items as $item) {
			foreach ($item as $label => $data) {
				$menuItem = new MenuItem();
				$menuItem->label = $label;
				$menuItem->pai = $pai;

				$menuItem->tipo = 'ENT';
				if (is_string($data)) {
					$menuItem->url = $data;
				} elseif (is_array($data)) {
					if (count($data) === 2 && key($data) === 0) {
						$menuItem->url = $data[0];
						$menuItem->icon = $data[1];
					} else {
						$menuItem->url = $data['url'] ?? null;
						$menuItem->cssStyle = $data['cssStyle'] ?? null;
						$menuItem->roles = $data['roles'] ?? null;
						$menuItem->icon = $data['icon'] ?? null;
					}
				}

				if (!empty($data['filhos'])) {
					$menuItem->tipo = 'PAI';
				}
				$menuItem->ordem = $ordem++;
				$this->doctrine->persist($menuItem);
				$this->doctrine->flush();

				if (!empty($data['filhos'])) {
					$this->parseMenuItems($data['filhos'], $menuItem);
				}
				$bla = 1;
			}
		}
	}

	private function truncateTable()
	{
		$connection = $this->doctrine->getConnection();
		$platform = $connection->getDatabasePlatform();
		$connection->executeStatement($platform->getTruncateTableSQL('cfg_menu_item', true));
	}

}
