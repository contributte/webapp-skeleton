<?php declare(strict_types = 1);

namespace App\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: self::NAME)]
class HelloCommand extends Command
{

	public const NAME = 'hello';

	protected function configure(): void
	{
		$this->setName(self::NAME);
		$this->setDescription('Hello world!');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$output->write('Hello world!');

		return 0;
	}

}
