<?php declare(strict_types = 1);

namespace App\Model\Security\Authenticator;

use App\Model\Database\Entity\User;
use App\Model\Database\EntityManager;
use App\Model\Exception\Runtime\AuthenticationException;
use App\Model\Security\Passwords;
use Nette\Security\IAuthenticator;
use Nette\Security\IIdentity;

final class UserAuthenticator implements IAuthenticator
{

	/** @var EntityManager */
	private $em;

	/** @var Passwords */
	private $passwords;

	public function __construct(EntityManager $em, Passwords $passwords)
	{
		$this->em = $em;
		$this->passwords = $passwords;
	}

	/**
	 * @param string[] $credentials
	 * @throws AuthenticationException
	 */
	public function authenticate(array $credentials): IIdentity
	{
		[$username, $password] = $credentials;

		$user = $this->em->getUserRepository()->findOneBy(['email' => $username]);

		if (!$user) {
			throw new AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);
		} elseif (!$user->isActivated()) {
			throw new AuthenticationException('The user is not active.', self::INVALID_CREDENTIAL);
		} elseif (!$this->passwords->verify($password, $user->getPasswordHash())) {
			throw new AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);
		}

		$user->changeLoggedAt();
		$this->em->flush();

		return $this->createIdentity($user);
	}

	protected function createIdentity(User $user): IIdentity
	{
		$user->changeLoggedAt();
		$this->em->flush();

		return $user->toIdentity();
	}

}
