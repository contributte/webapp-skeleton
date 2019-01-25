<?php declare(strict_types = 1);

namespace App\Domain\Order;

use App\Domain\Order\Event\OrderCreated;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Tracy\Debugger;

class OrderLogSubscriber implements EventSubscriberInterface
{

	/**
	 * @return mixed[]
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			OrderCreated::NAME => 'onOrderCreated',
		];
	}

	public function onOrderCreated(OrderCreated $event): void
	{
		Debugger::log($event, 'info');
		Debugger::barDump($event);
	}

}
