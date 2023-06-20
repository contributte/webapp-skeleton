<?php declare(strict_types = 1);

namespace App\UI\Modules\Admin\Home;

use App\Domain\Order\Event\OrderCreated;
use App\UI\Modules\Admin\BaseAdminPresenter;
use Nette\Application\UI\Form;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class HomePresenter extends BaseAdminPresenter
{

	/** @var EventDispatcherInterface @inject */
	public EventDispatcherInterface $dispatcher;

	protected function createComponentOrderForm(): Form
	{
		$form = new Form();

		$form->addText('order', 'Order name')
			->setRequired(true);
		$form->addSubmit('send', 'OK');

		$form->onSuccess[] = function (Form $form): void {
			$this->dispatcher->dispatch(new OrderCreated($form->values->order), OrderCreated::NAME);
		};

		return $form;
	}

}
