<?php declare(strict_types = 1);

namespace App\Model\Database;

use App\Model\Database\Query\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;

class QueryManager
{

	public function __construct(
		private EntityManagerInterface $em
	)
	{
	}

	public function findOne(AbstractQuery $query): mixed
	{
		return $query->doQuery($this->em)->getSingleResult();
	}

	public function findAll(AbstractQuery $query): mixed
	{
		return $query->doQuery($this->em)->getResult();
	}

}
