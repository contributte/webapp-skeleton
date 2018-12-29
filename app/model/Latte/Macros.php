<?php declare(strict_types = 1);

namespace App\Model\Latte;

use Latte\Compiler;
use Latte\Macros\MacroSet;

final class Macros extends MacroSet
{

	public static function register(Compiler $compiler): void
	{
		$compiler = new static($compiler);
	}

}
