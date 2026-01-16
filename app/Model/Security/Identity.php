<?php declare(strict_types = 1);

namespace App\Model\Security;

use Nette\Security\SimpleIdentity as NetteIdentity;

class Identity extends NetteIdentity
{

	public function getFullname(): string
	{
		/** @phpstan-ignore method.deprecatedClass */
		$data = $this->getData();

		return sprintf('%s %s', $data['name'] ?? '', $data['surname'] ?? '');
	}

}
