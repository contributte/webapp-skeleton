<?php declare(strict_types = 1);

namespace App\Model\Database;

use App\Model\Database\Repository\AbstractRepository;
use Doctrine\Common\Persistence\ObjectRepository;
use Nettrine\ORM\EntityManagerDecorator;

class EntityManager extends EntityManagerDecorator
{

	use TRepositories;

	/**
	 * @internal
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 * @param string $entityName
	 * @return ObjectRepository|AbstractRepository
	 */
	public function getRepository($entityName): ObjectRepository
	{
		return parent::getRepository($entityName);
	}

}
