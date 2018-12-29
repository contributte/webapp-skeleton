<?php declare(strict_types = 1);

namespace Tests\Toolkit\TestCase;

use Nette\DI\Container;

abstract class BaseContainerTestCase extends BaseTestCase
{

	/** @var Container */
	protected $container;

	public function __construct(Container $container)
	{
		$this->container = $container;
	}

	protected function getService(string $class): object
	{
		if (strpos($class, '\\')) {
			return $this->container->getByType($class);
		} else {
			return $this->container->getService($class);
		}
	}

}
