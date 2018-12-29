<?php declare(strict_types = 1);

namespace Database\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture as BaseFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Faker\Factory;
use Faker\Generator;
use Nette\DI\Container;
use Nettrine\Fixtures\ContainerAwareInterface;

abstract class AbstractFixture extends BaseFixture implements ContainerAwareInterface, OrderedFixtureInterface
{

	/** @var Container */
	protected $container;

	/** @var Generator */
	protected $faker;

	/**
	 * AbstractFixture constructor
	 */
	public function __construct()
	{
		$this->faker = Factory::create();
	}

	public function setContainer(Container $container): void
	{
		$this->container = $container;
	}

	public function getOrder(): int
	{
		return 0;
	}

}
