<?php declare(strict_types = 1);

namespace App\Model\Security;

use Nette\Security\SimpleIdentity as NetteIdentity;

class Identity extends NetteIdentity
{

	public function getFullname(): string
	{
		return sprintf('%s %s', $this->data['name'] ?? '', $this->data['surname'] ?? '');
	}

}
