<?php declare(strict_types = 1);

namespace App\Modules\Front\Test;

final class UserDetail extends Widget
{

	/** @var int */
	private $id;

	protected function setupProps(int $id): void
	{
		$this->id = $id;
	}

	public function render(): void
	{
		$this->template->setFile(__DIR__ . '/userdetail.latte');
		$this->template->id = $this->id;
		$this->template->render();
	}

}
