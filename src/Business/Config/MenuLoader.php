<?php

namespace CrosierSource\CrosierLibCoreBundle\Business\Config;

use CrosierSource\CrosierLibCoreBundle\Entity\Config\MenuItem;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Yaml\Yaml;

readonly class MenuLoader
{

	public function __construct(
		#[Autowire()]
		private EntityManagerInterface $doctrine,
		private LoggerInterface        $logger,
	)
	{
		// ...
	}

	public function load(): void
	{
		$filePath = __DIR__ . '/../../../../' . $_SERVER['CROSIERAPP_ID'] . '/menu/menu.yaml';
		if (!file_exists($filePath)) {
			$this->logger->error('Arquivo de menu não encontrado: ' . $filePath);
			return;
		}
		$menuData = Yaml::parseFile($filePath)['menu_items'] ?? [];
		$this->deleteMenuItensDoCrosierApp();
		$this->parseMenuItems($menuData);
	}

	private function parseMenuItems(array $items, ?MenuItem $pai = null): void
	{
		$ordem = 1;
		foreach ($items as $item) {
			foreach ($item as $label => $data) {
				$menuItem = new MenuItem();
				$menuItem->crosierApp = $_SERVER['CROSIERAPP_ID'];
				$this->logger->info("Incluindo: " . $label);
				$menuItem->label = $label;
				$menuItem->pai = $pai;
				$ordem = $menuItem?->ordem ?? $data['ordem'] ?? $ordem;

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
			}
		}
	}

	private function deleteMenuItensDoCrosierApp(): void
	{
		try {
			$this->logger->info('Deletando itens de menu do CrosierApp: ' . $_SERVER['CROSIERAPP_ID']);
			$connection = $this->doctrine->getConnection();
			$connection->executeStatement("DELETE FROM cfg_menu_item WHERE crosier_app = :crosier_app", ['crosier_app' => $_SERVER['CROSIERAPP_ID']]);
		} catch (Exception $e) {
			$this->logger->error('Erro ao deletar os itens de menu do CrosierApp: ' . $_SERVER['CROSIERAPP_ID'] . ' (' . $e->getMessage() . ')');
		}
	}


}
