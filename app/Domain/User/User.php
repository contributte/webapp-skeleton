<?php declare(strict_types = 1);

namespace App\Domain\User;

use App\Model\Database\Entity\AbstractEntity;
use App\Model\Database\Entity\TCreatedAt;
use App\Model\Database\Entity\TId;
use App\Model\Database\Entity\TUpdatedAt;
use App\Model\Exception\Logic\InvalidArgumentException;
use App\Model\Security\Identity;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Domain\User\UserRepository")
 * @ORM\Table(name="`user`")
 * @ORM\HasLifecycleCallbacks
 */
class User extends AbstractEntity
{

	use TId;
	use TCreatedAt;
	use TUpdatedAt;

	public const ROLE_ADMIN = 'admin';
	public const ROLE_USER = 'user';

	public const STATE_FRESH = 1;
	public const STATE_ACTIVATED = 2;
	public const STATE_BLOCKED = 3;

	public const STATES = [self::STATE_FRESH, self::STATE_BLOCKED, self::STATE_ACTIVATED];

	/** @ORM\Column(type="string", length=255, nullable=FALSE, unique=false) */
	private string $name;

	/** @ORM\Column(type="string", length=255, nullable=FALSE, unique=false) */
	private string $surname;

	/** @ORM\Column(type="string", length=255, nullable=FALSE, unique=TRUE) */
	private string $email;

	/** @ORM\Column(type="string", length=255, nullable=FALSE, unique=TRUE) */
	private string $username;

	/** @ORM\Column(type="integer", length=10, nullable=FALSE) */
	private int $state;

	/** @ORM\Column(type="string", length=255, nullable=FALSE) */
	private string $password;

	/** @ORM\Column(type="string", length=255, nullable=FALSE) */
	private string $role;

	/**
	 * @var DateTime|NULL
	 * @ORM\Column(type="datetime", nullable=TRUE)
	 */
	private ?DateTime $lastLoggedAt = null;

	public function __construct(string $name, string $surname, string $email, string $username, string $passwordHash)
	{
		$this->name = $name;
		$this->surname = $surname;
		$this->email = $email;
		$this->username = $username;
		$this->password = $passwordHash;

		$this->role = self::ROLE_USER;
		$this->state = self::STATE_FRESH;
	}

	public function changeLoggedAt(): void
	{
		$this->lastLoggedAt = new DateTime();
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function getUsername(): string
	{
		return $this->username;
	}

	public function changeUsername(string $username): void
	{
		$this->username = $username;
	}

	public function getLastLoggedAt(): ?DateTime
	{
		return $this->lastLoggedAt;
	}

	public function getRole(): string
	{
		return $this->role;
	}

	public function setRole(string $role): void
	{
		$this->role = $role;
	}

	public function getPasswordHash(): string
	{
		return $this->password;
	}

	public function changePasswordHash(string $password): void
	{
		$this->password = $password;
	}

	public function block(): void
	{
		$this->state = self::STATE_BLOCKED;
	}

	public function activate(): void
	{
		$this->state = self::STATE_ACTIVATED;
	}

	public function isActivated(): bool
	{
		return $this->state === self::STATE_ACTIVATED;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getSurname(): string
	{
		return $this->surname;
	}

	public function getFullname(): string
	{
		return $this->name . ' ' . $this->surname;
	}

	public function rename(string $name, string $surname): void
	{
		$this->name = $name;
		$this->surname = $surname;
	}

	public function getState(): int
	{
		return $this->state;
	}

	public function setState(int $state): void
	{
		if (!in_array($state, self::STATES, true)) {
			throw new InvalidArgumentException(sprintf('Unsupported state %s', $state));
		}

		$this->state = $state;
	}

	public function getGravatar(): string
	{
		return 'https://www.gravatar.com/avatar/' . md5($this->email);
	}

	public function toIdentity(): Identity
	{
		return new Identity($this->getId(), [$this->role], [
			'email' => $this->email,
			'name' => $this->name,
			'surname' => $this->surname,
			'state' => $this->state,
			'gravatar' => $this->getGravatar(),
		]);
	}

}
