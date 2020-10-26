<?php

namespace App\Commands;

use Phinx\Migration\Manager as MigrationManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Migrate extends Command
{
	protected static $defaultName = "migrate";

	protected function configure(): void
	{
		$this->addOption("ver", null, InputOption::VALUE_REQUIRED, "Version number to migrate to.");
		$this->addOption("fake", null, InputOption::VALUE_NONE, "Do a dry run of the migrations.");
		$this->setDescription("Run database migrations");
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		/**
		 * @var MigrationManager $migrationManager
		 */
		$migrationManager = \container(MigrationManager::class);
		$migrationManager->setOutput($output);

		$migrationManager->migrate(
			"default"
		);

		$migrationManager->printStatus("default");

		return 0;
	}
}