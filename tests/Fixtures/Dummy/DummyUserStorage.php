<?php declare(strict_types = 1);

namespace Tests\Fixtures\Dummy;

use Nette\Security\IIdentity;
use Nette\Security\UserStorage;

final class DummyUserStorage implements UserStorage
{

	/** @var IIdentity|NULL */
	private ?IIdentity $identity = null;

	public function saveAuthentication(IIdentity $identity): void
	{
		$this->identity = $identity;
	}

	public function clearAuthentication(bool $clearIdentity): void
	{
		$this->identity = null;
	}

	/**
	 * @return array{bool, ?IIdentity, ?int}
	 */
	public function getState(): array
	{
		return [$this->identity !== null, $this->identity, null];
	}

	public function setExpiration(?string $expire, bool $clearIdentity): void
	{
		// Do nothing
	}

	public function setNamespace(string $namespace): self
	{
		return $this;
	}

}
