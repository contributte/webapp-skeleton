<?php declare(strict_types = 1);

namespace App\Model\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HelloCommand extends Command
{

	protected function configure(): void
	{
		$this->setName('hello');
		$this->setDescription('Hello world!');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$output->write('Hello world!');

		return 0;
	}

}
