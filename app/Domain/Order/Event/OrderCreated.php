<?php declare(strict_types = 1);

namespace App\Domain\Order\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class OrderCreated extends Event
{

	public const NAME = 'order.created';

	private string $order;

	public function __construct(string $order)
	{
		$this->order = $order;
	}

	public function getOrder(): string
	{
		return $this->order;
	}

}
