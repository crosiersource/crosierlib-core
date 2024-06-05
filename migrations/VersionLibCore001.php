<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


class VersionLibCore001 extends AbstractMigration
{

	public function up(Schema $schema): void
	{
		$this->addSql(file_get_contents(__DIR__ . '/VersionLibCore001.sql'));
	}

}
