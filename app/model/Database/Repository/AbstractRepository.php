<?php declare(strict_types = 1);

namespace App\Model\Database\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * @phpstan-template TEntityClass of object
 * @phpstan-extends EntityRepository<TEntityClass>
 */
abstract class AbstractRepository extends EntityRepository
{

	/**
	 * Fetches all records like $key => $value pairs
	 *
	 * @param mixed[] $criteria
	 * @param mixed[] $orderBy
	 * @return mixed[]
	 */
	public function findPairs(?string $key, string $value, array $criteria = [], array $orderBy = []): array
	{
		if ($key === null) {
			$key = $this->getClassMetadata()->getSingleIdentifierFieldName();
		}

		$qb = $this->createQueryBuilder('e')
			->select(['e.' . $value, 'e.' . $key])
			->resetDQLPart('from')
			->from($this->getEntityName(), 'e', 'e.' . $key);

		foreach ($criteria as $k => $v) {
			if (is_array($v)) {
				$qb->andWhere(sprintf('e.%s IN(:%s)', $k, $k))->setParameter($k, array_values($v));
			} else {
				$qb->andWhere(sprintf('e.%s = :%s', $k, $k))->setParameter($k, $v);
			}
		}

		foreach ($orderBy as $column => $order) {
			$qb->addOrderBy($column, $order);
		}

		return array_map(function ($row) {
			return reset($row);
		}, $qb->getQuery()->getArrayResult());
	}

}
