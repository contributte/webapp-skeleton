<?php declare(strict_types = 1);

namespace App\Model\Latte;

use Nette\Neon\Neon;
use Nette\StaticClass;
use Nette\Utils\Json;

final class Filters
{

	use StaticClass;

	/**
	 * @param mixed $value
	 */
	public static function neon($value): string
	{
		return Neon::encode($value, Neon::BLOCK);
	}

	/**
	 * @param mixed $value
	 */
	public static function json($value): string
	{
		return Json::encode($value);
	}

}
