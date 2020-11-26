<?php declare(strict_types = 1);

namespace App\Modules\Front\Test;

final class UserGrid extends Widget
{

	public $onChange = [];

	public function handleChange(): void
	{
		$this->onChange($this);
	}

	public function render(): void
	{
		$this->template->setFile(__DIR__ . '/usergrid.latte');
		$this->template->render();
	}

}
