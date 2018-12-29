<?php declare(strict_types = 1);

namespace App\Model\Database\Entity\Attributes;

use App\Model\Utils\DateTime;
use Doctrine\ORM\Mapping as ORM;

trait TCreatedAt
{

	/**
	 * @var DateTime
	 * @ORM\Column(type="datetime", nullable=FALSE)
	 */
	protected $createdAt;

	public function getCreatedAt(): DateTime
	{
		return $this->createdAt;
	}

	/**
	 * Doctrine annotation
	 *
	 * @ORM\PrePersist
	 * @internal
	 */
	public function setCreatedAt(): void
	{
		$this->createdAt = new DateTime();
	}

}
