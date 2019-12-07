<?php declare(strict_types = 1);

namespace Tests\Toolkit\Nette;

use DateTimeInterface;
use Nette\Security\IIdentity;
use Nette\Security\IUserStorage;

final class DummyUserStorage implements IUserStorage
{

	/** @var bool */
	private $authenticated = false;

	/** @var IIdentity|NULL */
	private $identity;

	/**
	 * @param bool $state
	 * @return static
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function setAuthenticated($state)
	{
		$this->authenticated = $state;

		return $this;
	}

	public function isAuthenticated(): bool
	{
		return $this->authenticated;
	}

	public function setIdentity(?IIdentity $identity): self
	{
		$this->identity = $identity;
		return $this;
	}

	public function getIdentity(): ?IIdentity
	{
		return $this->identity;
	}

	/**
	 * @param string|int|DateTimeInterface $time
	 * @return static
	 */
	public function setExpiration($time, int $flags = 0)
	{
		return $this;
	}

	public function getLogoutReason(): ?int
	{
		return null;
	}

	public function setNamespace(string $namespace): self
	{
		return $this;
	}

}
