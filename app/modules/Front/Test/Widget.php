<?php declare(strict_types = 1);

namespace App\Modules\Front\Test;

use App\Model\Exception\Runtime\InvalidStateException;
use Nette\Application\UI\Control;

abstract class Widget extends Control
{

	public function setProps(...$args): void
	{
		if (!method_exists($this, 'setupProps')) {
			throw new InvalidStateException(
				sprintf('Unknown method "%s::setupProps()"', get_called_class())
			);
		}

		call_user_func_array([$this, 'setupProps'], $args);
	}

}
