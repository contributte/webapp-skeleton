<?php declare(strict_types = 1);

namespace Tests\Toolkit\Nette;

use Nette\Security\IIdentity;
use Nette\Security\UserStorage;

final class DummyUserStorage implements UserStorage
{

	/** @var IIdentity|NULL */
	private $identity;

	public function saveAuthentication(IIdentity $identity): void
	{
		$this->identity = $identity;
	}

	public function clearAuthentication(bool $clearIdentity): void
	{
		$this->identity = null;
	}

	public function getState(): array
	{
		return [$this->identity !== null, $this->identity, null];
	}

	public function setExpiration(?string $expire, bool $clearIdentity): void
	{
	}

	public function setNamespace(string $namespace): self
	{
		return $this;
	}

}
