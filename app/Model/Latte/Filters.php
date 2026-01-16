<?php declare(strict_types = 1);

namespace App\Model\Latte;

use DateTimeInterface;
use Nette\Neon\Neon;
use Nette\StaticClass;
use Nette\Utils\Json;

final class Filters
{

	use StaticClass;

	public static function datetime(DateTimeInterface|string|int|null $value, string $format = 'j. n. Y'): string
	{
		if ($value === null) {
			return '';
		}

		if (is_string($value) || is_int($value)) {
			$value = new \DateTimeImmutable(is_int($value) ? '@' . $value : $value);
		}

		return $value->format($format);
	}

	public static function neon(mixed $value): string
	{
		return Neon::encode($value, true);
	}

	public static function json(mixed $value): string
	{
		return Json::encode($value);
	}

}
