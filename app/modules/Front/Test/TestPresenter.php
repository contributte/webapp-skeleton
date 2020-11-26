<?php declare(strict_types = 1);

namespace App\Modules\Front\Test;

use App\Modules\Front\BaseFrontPresenter;
use Nette\ComponentModel\IComponent;

final class TestPresenter extends BaseFrontPresenter
{

	/** @var ComponentCreator @inject */
	public $cc;

	protected function startup()
	{
		parent::startup();

		$this->cc->onCreate('userdetail', function () {
			return 1;
		});
		$this->cc->onAnchor('usergrid', function (UserGrid $control): void {
			$control->onChange[] = function () {
				$this['userdetail']->redrawControl();
			};
		});
	}

	protected function createComponent($name): ?IComponent
	{
		$widget = $this->cc->create($name);
		if ($widget) return $widget;

		return parent::createComponent($name);
	}

}
