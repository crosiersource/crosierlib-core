<?php

namespace CrosierSource\CrosierLibCoreBundle\Command\Core;

use CrosierSource\CrosierLibCoreBundle\Business\Config\MenuLoader;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MenuLoaderCommand extends Command
{
	private LoggerInterface $logger;

	private MenuLoader $menuLoader;

	public function __construct(
		LoggerInterface $logger,
		MenuLoader $menuLoader
	)
	{
		$this->logger = $logger;
		$this->menuLoader = $menuLoader;
		parent::__construct();
	}

	protected function configure(): void
	{
		$this
			->setName('crosier:menuloader')
			->setDescription('Carrega o menu a partir do arquivo menu/menu.yaml.');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		try {
			$this->menuLoader->load();
			return 1;
		} catch (\Exception $e) {
			$output->writeln('Erro: ' . $e->getMessage());
			$this->logger->debug($e->getMessage());
			return -1;
		}
	}
}

