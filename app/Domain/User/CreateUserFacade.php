<?php declare(strict_types = 1);

namespace App\Domain\User;

use App\Model\Database\EntityManagerDecorator;
use App\Model\Security\Passwords;

class CreateUserFacade
{

	private EntityManagerDecorator $em;

	public function __construct(
		EntityManagerDecorator $em
	)
	{
		$this->em = $em;
	}

	/**
	 * @param array<string, scalar> $data
	 */
	public function createUser(array $data): User
	{
		// Create User
		$user = new User(
			(string) $data['name'],
			(string) $data['surname'],
			(string) $data['email'],
			(string) $data['username'],
			Passwords::create()->hash(strval($data['password'] ?? md5(microtime())))
		);

		// Set role
		if (isset($data['role'])) {
			$user->setRole((string) $data['role']);
		}

		// Save user
		$this->em->persist($user);
		$this->em->flush();

		return $user;
	}

}
