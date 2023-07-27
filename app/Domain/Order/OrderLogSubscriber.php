<?php declare(strict_types = 1);

namespace App\Domain\Order;

use App\Domain\Order\Event\OrderCreated;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Tracy\Debugger;

class OrderLogSubscriber implements EventSubscriberInterface
{

	/**
	 * {@inheritdoc}
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			OrderCreated::NAME => [
				['onOrderCreatedBefore', 100],
				['onOrderCreated', 0],
				['onOrderCreatedAfter', -100],
			],
		];
	}

	public function onOrderCreatedBefore(OrderCreated $event): void
	{
		Debugger::barDump('BEFORE');
	}

	public function onOrderCreated(OrderCreated $event): void
	{
		Debugger::log($event, 'info');
		Debugger::barDump($event);
	}

	public function onOrderCreatedAfter(OrderCreated $event): void
	{
		Debugger::barDump('AFTER');
	}

}
