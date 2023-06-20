<?php declare(strict_types = 1);

namespace App\Model\Latte;

use Latte\Engine;

final class FilterExecutor
{

	private Engine $latte;

	public function __construct(Engine $latte)
	{
		$this->latte = $latte;
	}

	/**
	 * @param mixed[] $args
	 */
	public function __call(string $name, array $args): mixed
	{
		return $this->latte->invokeFilter($name, $args);
	}

	public function __get(string $name): mixed
	{
		return $this->latte->invokeFilter($name, []);
	}

}
