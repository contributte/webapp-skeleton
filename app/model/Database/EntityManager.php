<?php declare(strict_types = 1);

namespace App\Model\Database;

use App\Model\Database\Repository\AbstractRepository;
use Doctrine\Persistence\ObjectRepository;
use Nettrine\ORM\EntityManagerDecorator;

class EntityManager extends EntityManagerDecorator
{

	use TRepositories;

	/**
	 * @param string $entityName
	 * @return AbstractRepository<T>|ObjectRepository<T>
	 * @internal
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 * @phpstan-template T of object
	 * @phpstan-param class-string<T> $entityName
	 * @phpstan-return ObjectRepository<T>
	 */
	public function getRepository($entityName): ObjectRepository
	{
		return parent::getRepository($entityName);
	}

}
