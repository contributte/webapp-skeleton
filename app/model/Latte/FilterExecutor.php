<?php declare(strict_types = 1);

namespace App\Model\Latte;

use Latte\Engine;

final class FilterExecutor
{

	/** @var Engine */
	private $latte;

	public function __construct(Engine $latte)
	{
		$this->latte = $latte;
	}

	/**
	 * @param mixed[] $args
	 * @return mixed
	 */
	public function __call(string $name, array $args)
	{
		return $this->latte->invokeFilter($name, $args);
	}

	/**
	 * @return mixed
	 */
	public function __get(string $name)
	{
		return $this->latte->invokeFilter($name, []);
	}

}
