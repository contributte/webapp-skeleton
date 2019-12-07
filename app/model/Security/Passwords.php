<?php declare(strict_types = 1);

namespace App\Model\Security;

use Nette\Security\Passwords as NettePasswords;

final class Passwords extends NettePasswords
{

	public static function create(): Passwords
	{
		return new Passwords();
	}

}
