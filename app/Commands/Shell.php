<?php

namespace App\Commands;

use Psy\Configuration;
use Psy\Shell as PsyShell;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class Shell extends Command
{
	protected static $defaultName = "shell";

	protected function configure(): void
	{
		$this->setAliases(['sh']);
		$this->setDescription("Starts a REPL shell");
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$shell = new PsyShell(new Configuration([
			"startupMessage" => "With great power comes great responsibility.",
		]));

		$shell->run();

		return 0;
	}
}